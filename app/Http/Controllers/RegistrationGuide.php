<?php

namespace App\Http\Controllers;

use App\Exceptions\MissingAnswersException;
use App\MatchingAnswer;
use App\MatchingAnswerType;
use App\MatchingQuestion;
use App\MatchingQuestionAnswerCondition;
use App\MatchingUserAnswer;
use App\SendinBlue\SendinBlueHandler;
use App\SendinBlue\SendinBlueTracker;
use App\User;
use App\Validation\PostalCodeValidator;
use App\Validation\UserAnswerValidationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Events\RegisteredSupporter;

class RegistrationGuide extends Controller
{
    const FIRST_STEP_NO = 1;
    const STEP_NO_FINISHED = -1;
    const STEP_NO_TERMINATED = -2;

    protected UserAnswerValidationService $userAnswerValidationService;

    public function __construct()
    {
        $this->middleware(['auth:api']);
        $this->userAnswerValidationService = new UserAnswerValidationService();
    }

    public function registerAsSupporter()
    {
        $user = Auth::user();
        if ($user) {
            $user->assignRole('supporter');
            event(new RegisteredSupporter($user));

            // just to be sure when changing the role back to MOURNERER the frabo will start in the first step
            $user->matching_step = self::FIRST_STEP_NO;
            $user->save();

            $this->eventBereavedBereavementCaregiver($user, 'supporter');

            return response()->json(['finished' => true]);
        } else {
            return response()->json('', 403);
        }
    }

    /**
     * Read the stored answers for the logged in user.
     *
     * @return Collection
     */
    protected function getPreviousAnswersFromDB(): Collection
    {
        $user = Auth::user();
        $user->load('matchingAnswers');

        return $user->matchingAnswers;
    }

    /**
     * @param Request $request
     * @param int|null $stepNo
     * @return JsonResponse
     * @throws Exception
     */
    public function step(Request $request, int $stepNo = null): JsonResponse
    {
        if (
            Auth::user()->matching_step < 0
        ) {
            return response()->json('', 403);
        }        

        $userAnswers = $request->user_answers;

        if ($stepNo === null && empty($userAnswers)) {
            // Initial call, start with the step where the user left
            $stepNo = Auth::user()->matching_step;
            $questions = $this->retrieveQuestionsForStep($stepNo);

            if ($stepNo > self::FIRST_STEP_NO) {
                // filter the questions
                $questions = $this->filterQuestionsMatchingConditions(
                    $questions,
                    $this->getPreviousAnswersFromDB()
                );
            }
            // TODO: remove for production just for testing
            //else {
            //$this->removeAllUserAnswers();
            //}


            // Hotfix for Golive: Remove Religion from questionaire
            // https://team.yesdevs.com/#/tasks/20058836
            // see also in filterQuestionsMatchingConditions method in this class
            if ($stepNo === 12) {
                $questions = array_filter($questions, function ($question) {
                    return $question['id'] !== 32;
                });
            }

            return response()->json([
                'questions' => $questions,
                'step' => $stepNo,
            ]);
        }

        // Clean given answers from invalid values
        $userAnswers = $this->filterUserAnswers($userAnswers);

        // Check if any of the given answers forces us to terminate the survey and leave if so
        if (! empty($userAnswers)) {
            $currentAnswerIds = array_keys(array_replace(...$userAnswers));

            if (MatchingAnswer::where('termination_condition', 1)->whereIn('id', $currentAnswerIds)->exists()) {
                $user = Auth::user();
                $user->matching_step = self::STEP_NO_TERMINATED;
                $user->save();
                return response()->json(['terminate' => true]);
            }
        }

        // Get answers from DB
        $givenAnswers = $this->getPreviousAnswersFromDB();

        //We are processing the results of a step
        $this->userAnswerValidationService->addValidator(
            UserAnswerValidationService::POSTALCODE_VALIDATOR_ID,
            function () use ($userAnswers) {
                return new PostalCodeValidator($userAnswers);
            }
        );

        try {
            $givenAnswersCombined = $givenAnswers->merge(
                $this->processAnswers($stepNo, $userAnswers, $givenAnswers)
            );
        } catch (MissingAnswersException $e) {
            return response()->json($e, 400);
        }

        $nextStepNo = $stepNo;
        do {
            // increment step counter
            $nextStepNo++;
            //Get the Questions for the next step
            $nextQuestions = $this->retrieveQuestionsForStep($nextStepNo);

            // If there are no, we are done and leave here
            if ($nextQuestions === null) {
                $user = Auth::user();
                $user->matching_step = self::STEP_NO_FINISHED;
                $user->save();
                $user->findMatches();

                $this->eventQuestionnaireFilledOut($user);

                return response()->json(['finished' => true]);
            }

            // filter the questions
            $nextQuestions = $this->filterQuestionsMatchingConditions(
                $nextQuestions,
                $givenAnswersCombined
            );

            // If the questions are empty but we have not returned with a 'finished' status,
            // we need to try a higher step_no because our conditions skipped a step. Therefore
            // we must repeat
        } while (empty($nextQuestions));
        $user = Auth::user();
        $user->matching_step = $nextStepNo;
        $user->save();

        return response()->json([
                'questions' => $nextQuestions,
                'step' => $nextStepNo,
            ]);
    }

    /**
     * Retrieves all questions for the next step from the DB
     * If there are no further questions it will return null.
     *
     * @param int $stepNo
     * @return Collection|null
     */
    protected function retrieveQuestionsForStep(int $stepNo): ?Collection
    {
        $questions = MatchingQuestion::whereHas(
            'additionalSteps',
            function ($query) use ($stepNo) {
                $query->where('step_no', $stepNo);
            }
        )
            ->whereNull('parent_id')
            ->with([
                'answers.differentAnswerType',
                'answers.parent.differentAnswerType',
                'answerType',
                'answers.conditionsRelation',
                'additionalSteps',
            ])
            ->orderBy('position', 'asc')
            ->get();

        if (! $questions->first()) {
            return null;
        }

        return $questions;
    }

    /**
     * Check various properties depending on the answer.
     * If the answer is not answered correctly false is returned.
     *
     * @param array $answer
     * @param array $userAnswers
     * @return bool
     */
    protected function validateAnswer(array $answer, array $userAnswers): bool
    {
        if (! array_key_exists($answer['id'], $userAnswers)) {
            //Log::info("Answer {$answerObj['id']} is not present");
            return !boolval($answer['obligatory']);
        }

        $givenAnswer = $userAnswers[$answer['id']];
        $validateAnswer = $this->userAnswerValidationService;

        return $validateAnswer($answer, $givenAnswer);
    }

    /**
     * Checks if all needed questions are answered, cleans the DB from any previous answers and stores the new ones.
     *
     * @param int $stepNo Number of the step the answers are submitted from
     * @param array $userAnswers the answers as posted by the frontend
     * @param Collection $previousAnswers
     * @return Collection
     * @throws MissingAnswersException
     */
    protected function processAnswers(int $stepNo, array $userAnswers, Collection $previousAnswers): Collection
    {
        // $userAnswers are the incoming answers from the current step

        // Retrieve Questions and possible answers for validation
        $answersToValidate = $this->retrieveQuestionsForStep($stepNo);
        $validationInput = $this->filterQuestionsMatchingConditions(
            $answersToValidate,
            $previousAnswers
        );

        $questionsForValidation = collect($validationInput);

        // Prepare an array for unanswered question Ids
        $missingQuestionIds = [];
        $questionValidationErrors = [];

        // Validation starts
        $questionsForValidation->each(function ($question) use ($userAnswers, &$missingQuestionIds, &$questionValidationErrors) {
            if (! $question['obligatory']) {
                // skip optional questions
                return;
            }

            //Log::info("Checking Question {$question['id']}");
            // If there are no answers at all exit early
            if (! array_key_exists($question['id'], $userAnswers)) {
                //Log::info("no answer at all");
                $missingQuestionIds[] = $question['id'];

                return;
            }

            //initialize the question as unanswered
            $hasAnswer = false;
            $validationErrors = [];
            $answersForQuestion = $userAnswers[$question['id']];
            collect($question['answers'])->each(function ($answer) use ($answersForQuestion, &$hasAnswer, &$validationErrors) {
                // If the answer has sub answers, check them instead
                if (! empty($answer['parent'])) {
                    // check for answertype
                    $isMixedCollapse = $answer['different_answer_type_id'] === MatchingAnswerType::MIXED_COLLAPSE_ID;

                    collect($answer['parent'])->each(
                        function ($subAnswer) use ($answersForQuestion, &$hasAnswer, &$validationErrors, $isMixedCollapse) {
                            // For mixed collapse all sub answers have to be set and valid
                            if ($isMixedCollapse) {
                                $hasAnswer = false;
                            }
                            if ($this->validateAnswer($subAnswer, $answersForQuestion)) {
                                //Log::info("Answered with {$subAnswer['id']}");
                                $hasAnswer = true;
                                if (! $isMixedCollapse) {
                                    return false; //break each() but only if not mixed collapse
                                }
                            } elseif ($this->userAnswerValidationService->hasError()) {
                                $validationErrors[] = $this->userAnswerValidationService->getLastValidationError();
                                $this->userAnswerValidationService->resetValidationError();
                            }
                        }
                    );
                    //Sub answers have been checked, so skip the normal one
                    return;
                }
                // The answer must be scalar, so check it
                //Log::info("Checking {$answer['id']}");
                if ($this->validateAnswer($answer, $answersForQuestion)) {
                    //Log::info("Answered with {$answer['id']}");
                    $hasAnswer = true;

                    return false; //break each()
                } elseif ($this->userAnswerValidationService->hasError()) {
                    $validationErrors[] = $this->userAnswerValidationService->getLastValidationError();
                    $this->userAnswerValidationService->resetValidationError();
                }
            });

            // The question is lacking an appropriate answer
            if (! $hasAnswer) {
                //Log::info("Question {$question['id']} is unanswered");
                $missingQuestionIds[] = $question['id'];
                $questionValidationErrors[$question['id']] = $validationErrors;

                return;
            }
        });

        //There are some unanswered questions
        if (! empty($missingQuestionIds)) {
            throw (new MissingAnswersException('Some questions haven\'t been answered'))
                ->addMissingQuestionIds($missingQuestionIds)
                ->addValidationErrors($questionValidationErrors);
        }

        // everything is fine, so store the answers
        return $this->storeUserAnswers($userAnswers);
    }

    /**
     * Delete all answers.
     */
    protected function removeAllUserAnswers(): void
    {
        MatchingUserAnswer::where('user_id', Auth::id())
            ->delete();
    }

    /**
     * Remove answers not properly provided by the frontend.
     *
     * @param array $userAnswers
     * @return array
     */
    protected function filterUserAnswers(array $userAnswers): array
    {
        $answers = [];

        foreach ($userAnswers as $questionId => $selectedAnswers) {
            $filteredAnswers = array_filter($selectedAnswers, function ($answerContent) {
                //check for date
                if (is_array($answerContent)) {
                    return array_key_exists('year', $answerContent)
                        && $answerContent['year'] >= 1920
                        && $answerContent['year'] <= (int) date('Y');
                }

                return $answerContent !== null && $answerContent !== false;
            });
            if (empty($filteredAnswers)) {
                continue;
            }
            $answers[$questionId] = $filteredAnswers;
        }

        return $answers;
    }

    /**
     * Prepare the posted answers and store them into the DB.
     * @param array $userAnswers the answers as posted by the frontend
     * @return Collection
     */
    protected function storeUserAnswers(array $userAnswers): Collection
    {
        $savedAnswers = collect();
        //Store the given answers
        foreach ($userAnswers as $questionId => $selectedAnswer) {
            foreach ($selectedAnswer as $answerId => $answerContent) {

                // The answer represents a date and therefore needs to be encoded
                if (is_array($answerContent)) {
                    $answerContent = json_encode($answerContent);
                }

                // write User answer
                $savedAnswers->add(MatchingUserAnswer::updateOrCreate(
                    ['user_id' => Auth::id(), 'answer_id' => $answerId],
                    [
                        'answer_text' => $answerContent,
                    ]
                ));
            }
        }

        return $savedAnswers;
    }

    /**
     * Replaces all Questions of the given set that do not match witch their conditions
     * and filters out all answers of valid questions that do not match the answers condition.
     *
     * @param Collection $questions
     * @param Collection $givenAnswers
     * @return array
     */
    protected function filterQuestionsMatchingConditions(
        Collection $questions,
        Collection $givenAnswers
    ): array {
        $questions = $questions->toArray();

        $result = array_filter(
            $questions,
            function ($question) use ($givenAnswers) {
                $conditions = $this->getConditionsForQuestion($question['id']);

                return $this->checkConditions($conditions, $givenAnswers);
            }
        );

        // Hotfix for Golive: Remove Religion from questionaire
        // https://team.yesdevs.com/#/tasks/20058836
        // see also in step method in this class
        $result = array_filter($result, function ($question) {
            return $question['id'] !== 32;
        });

        $result = array_map(
            function ($question) use ($givenAnswers) {
                $question['answers'] = array_filter($question['answers'], function ($answer) use ($givenAnswers) {
                    if ($answer['conditions_relation_id'] !== null) {
                        //Log::info("Checking Condition for Answer {$answer['id']}");
                        return $this->checkConditions(
                            $this->getConditionsForAnswer($answer['id']),
                            $givenAnswers
                        );
                    }

                    return true;
                });

                return $question;
            },
            $result
        );

        return $result;
    }

    /**
     * Reads all conditions for a answer_id.
     *
     * @param int $answer_id
     * @return Collection
     */
    protected function getConditionsForAnswer(int $answer_id): Collection
    {
        return MatchingQuestionAnswerCondition::where('related_answer_id', $answer_id)
            ->orderBy('position', 'asc')
            ->get();
    }

    /**
     * Reads all conditions for a Question.
     *
     * @param int $question_id
     * @return Collection
     */
    protected function getConditionsForQuestion(int $question_id): Collection
    {
        return MatchingQuestionAnswerCondition::where('related_question_id', $question_id)
            ->orderBy('position', 'asc')
            ->get();
    }

    /**
     * Checks the given conditions against the user's answers
     * by rendering a logical expression and using eval to execute it.
     *
     * @param $conditions
     * @param $givenAnswers
     * @return bool
     * @throws Exception When the rendered condition seems to be manipulated
     */
    private function checkConditions(Collection $conditions, Collection $givenAnswers): bool
    {
        if ($conditions->isEmpty()) {
            return true;
        } // show related object!

        // Convert conditions into string
        $conditionRender = '';
        $conditionRenderDebug = '';
        $conditions->each(
            function ($condition_obj) use (&$conditionRender, &$conditionRenderDebug, $givenAnswers) {
                $operator = $condition_obj->operator === '=' ? '===' : $condition_obj->operator;
                $expected = $condition_obj->answer_content;
                $actual_value = 0;
                if (
                    $givenAnswers->contains('answer_id', $condition_obj->answer_id)

                ) {
                    $answer = $givenAnswers->firstWhere('answer_id', $condition_obj->answer_id);
                    if (is_numeric($answer->answer_text)) {
                        $actual_value = (int) $answer->answer_text;
                    } else {
                        $actual_value = 1;
                    }
                }

                if (! empty($conditionRender)) {
                    $logicalOperator = empty($condition_obj->operator_several_conditions) ? ' AND ' : " {$condition_obj->operator_several_conditions} ";
                    $conditionRender .= $logicalOperator;
                    $conditionRenderDebug .= $logicalOperator;
                }

                $conditionRender .= "{$condition_obj->before}{$actual_value} {$operator} {$expected}{$condition_obj->after}";
                $conditionRenderDebug .= "{$condition_obj->before}{$condition_obj->answer->code} {$operator} {$expected}{$condition_obj->after}";
            }
        );

        //Try to self-heal brackets
        $numOpenBrakets = substr_count($conditionRender, '(');
        $numClosedBrakets = substr_count($conditionRender, ')');
        if ($numOpenBrakets !== $numClosedBrakets) {
            if ($numOpenBrakets > $numClosedBrakets) {
                $conditionRender .= ')';
            } else {
                $conditionRender = '('.$conditionRender;
            }
        }

        // Security, because eval is very evil
        // notice for future generations: never ever even think about using eval
        // eval might hold the power to one time destroy all of our technological advancements
        // so if you can read this and you are in the future always remember not to use eval
        // but.. also.. don't refactor this
        // for this time eval is ok because I'm now going to add some security ;-)
        if (! preg_match('/^([0-9()]*|={3}|<=|>=|<|>|AND|OR|\s)+$/', $conditionRender)) {
            throw new Exception('The render of the conditions is not clean! Possible code injection?!');
        }
        // The eval is wrapped, so that even if someone manages to sneak smth passed the guard above
        // the only variable he is allowed to access is $conditionRender
        $condition_bool = (function () use ($conditionRender) {
            return eval("return ({$conditionRender});");
        })();

        //Log::info('Condition debug', ['condition' => $conditionRenderDebug, 'result' => $condition_bool]);
        //Log::info('Condition debug', ['condition' => $conditionRender, 'result' => $condition_bool]);
        return $condition_bool;
    }

    public function confirmAsMourner(Request $request) {
        $user = Auth::user();
        $this->eventBereavedBereavementCaregiver($user, 'mourner');
        return response()->json(null, 200);
    }

    protected function eventBereavedBereavementCaregiver(User $user, $type  = 'mourner') {
        $sendinBlue = new SendinBlueHandler($user);
        $sendinBlue->emitBereavedBereavementCaregiver($type);
    }

    protected function eventQuestionnaireFilledOut(User $user) {
        $sendinBlue = new SendinBlueHandler($user);
        $sendinBlue->emitQuestionnaireFilledOut();
    }
}
