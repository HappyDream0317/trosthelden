<?php

namespace App\Exports;

use App\MatchingAnswer;
use App\MatchingQuestion;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UserAnswerExport implements FromCollection
{
    private $allAnswers;
    private $sheet;
    const LABELS = [
        'PLZ',
        'DATUM'
    ];

    const QUESTION_IDS = [
        1,  // Ich suche Trost in der Trauer um ...
//        2,  // Die Art unserer Beziehung
        3,  // Das Geschlecht des verstorbenen Menschen:
        4,  // Er/Sie starb im Alter von:
        5,  // Der von mir betrauerte Mensch starb:
        6,  // Der von mir betrauerte Mensch starb am
        7,  // Mein Geschlecht:
        8,  // Geburtsdatum
        9,  // Ich lebe in:
        11, // Meine Nationalität:
        12, // Ich bin ... (ledig, verheiratet, etc)
        13, // Ich wohne ... (alleine, mit jemandem anderen)
        14, // Ich habe Kinder (leibliche oder/und  Adoptiv-, Stief-, Pflegekinder):
        29, // Mein höchster Bildungsabschluss:
        30, // Zu meiner beruflichen Tätigkeit: Ich bin …
        31, // Mein (ggf. früherer) Beruf:
        33, // Spiritualität hat einen Platz in meinem Leben – mit oder ohne Religion:
        34, // Auf den Tod eines geliebten Menschen kann man sich nicht wirklich vorbereiten. Selbst dann nicht, wenn man lange vorher ahnt oder weiß, dass er sterben wird. Und doch macht die Erwartung einen Unterschied. Bei mir ist das so: Der Mensch, um den ich trauere, starb …
        35, // Unabhängig davon habe ich das starke Gefühl, dass ich mich nicht richtig verabschieden konnte:
        36, // Der von mir betrauerte Mensch starb in meinem Beisein:
        37, // Manche Trauernde haben das Sterben unmittelbar miterlebt, manche waren räumlich so weit entfernt, dass es ihnen völlig unwirklich erscheint. Der von mir betrauerte Mensch starb gefühlt…
        38, // Ich habe den betrauerten Menschen im Sterben (mit)gepflegt, und diese Art von Nähe tat mir nicht gut.
        39, // Der Tod des Menschen, um den ich trauere, wäre vermeidbar gewesen, weil (mutmaßlich) andere verantwortlich dafür sind.
        41, // Mich beschäftigen (unbestimmte) Schuldgefühle, was den Tod des von mir betrauerten Menschen betrifft.
        42, // Magst Du in ein paar Worten Näheres zu den Gründen sagen?
        43, // Der Mensch, um den ich trauere, lebte mit mir in einem gemeinsamen Haushalt:
        44, // Meine Beziehung zu dem verstorbenen Menschen war nicht nur mit guten, sondern auch mit unguten Gefühlen behaftet:
        45, // Neben der Trauer belasten mich andere Umstände oder lebensverändernde Ereignisse:
        46, // Magst Du in ein paar Worten Näheres zu den Umständen oder den Ereignissen sagen?
    ];

    const DEEPER_QUESTIONS = [
        5
    ];

    public function collection()
    {
        $this->allAnswers = MatchingAnswer::all();
        $allQuestions = MatchingQuestion::whereIn('id', self::QUESTION_IDS)->pluck('question')->all();

        $this->sheet = [];
        $this->sheet[] = ['ID', 'Username', 'Created At', ...$allQuestions];

        $users = User::with('matchingAnswers', 'matchingAnswers.answer')->get()->filter(function ($value, $key) {
//            return $value->id === 541; // your test user id
            return $value->matching_step == -1 &&
                !$value->has_nova_access &&
                !strpos($value->email, '@yesdevs.com') &&
                !strpos($value->email, '@trosthelden.de');
        });

        if ($users) {
            foreach ($users as $user) {
                $row = [];
                $matchingAnswers = $user->matchingAnswers->all();

                if ($matchingAnswers) {

                    /**
                     * This approach guarantees that we will always have the same amount of answers
                     * in the users array
                     */
                    foreach (self::QUESTION_IDS as $qId) {
                        foreach ($matchingAnswers as $answer) {
                            if ($answer->answer->question_id && $answer->answer->parent_id === null) {

                                /**
                                 * Direct answers to the question
                                 */

                                $questionId = $answer->answer->question_id;
                            } else {

                                /**
                                 * When there is no direct relation we have to get the parent_id what is primary key
                                 * of the main answer and then we can filter for the question id
                                 */

                                $parentAnswerId = $answer->answer->parent_id;
                                $parentAnswers = $this->allAnswers->filter(function ($value, $key) use ($parentAnswerId) {
                                    return $value->id === $parentAnswerId;
                                })->first();

                                $questionId = $parentAnswers->question_id;
                            }

                            if ($questionId !== $qId) {
                                continue;
                            }

//                            This may help you deubgging
//                            if( $qId === 34 && $questionId === 34 ){
//                                echo '<pre style="color:#000;">';
//                                var_dump( $questionId );
//                                var_dump( $answer->answer->id );
//                                var_dump( $answer->answer->answer );
//                                var_dump( $answer->answer->additional_text );
//                                var_dump( $answer->answer_text );
//                                echo '</pre>----------';
//                            }

                            if (in_array($questionId, self::QUESTION_IDS)) {

                                /**
                                 * $answer: the users given answer
                                 * $answer->answer: the answer from the frabo with default answer (mostly text)
                                 *
                                 * Normal Case: In this case check if there is a pre-defined answer, if so take the
                                 * value if not there must be a custom (individual) user input
                                 */

                                $originalOrCustomAnswer =
                                    $answer->answer->answer !== '' ?
                                    $answer->answer->answer :
                                    $answer->answer_text;

                                /**
                                 * Range Slider Case: check the answer again and use the answer_text
                                 * if its a range slider answer -_-
                                 */

                                $originalOrCustomAnswer =
                                    strpos($originalOrCustomAnswer, 'min') ?
                                    $answer->answer_text :
                                    $originalOrCustomAnswer;

                                /**
                                 * Check for dates and just return the year
                                 */

                                $originalOrCustomAnswer =
                                    strpos($originalOrCustomAnswer, 'year') ?
                                    $this->addCustomAnswer($originalOrCustomAnswer) :
                                    $originalOrCustomAnswer;

                                /**
                                 * This stores the main answer to the question
                                 */

                                $row[$questionId] = $originalOrCustomAnswer;

                                /**
                                 * Now check if there are additional individual information for the main answer
                                 * Like: died because of drugs -> alcohol
                                 * Then we need an additional column in the table head.
                                 * It will be called like the question but " Details" added to it.
                                 */

                                if ($answer->answer->additional_text) {
                                    $row[$questionId.'+'] = $this->addCustomAnswer($answer->answer_text);
                                    $this->extendTableHead($questionId);
                                } elseif (in_array($questionId, self::DEEPER_QUESTIONS)) {

                                    /**
                                     * In this case we always have to create an array entry for the same question
                                     * that don't have an additinal information to keep the same amount of answers
                                     * in the users array.
                                     */

                                    $row[$questionId.'+'] = '';
                                }

                                /**
                                 * The column label holds additional individual information too.
                                 * Then we don't need to add an additional column to the table header
                                 */

                                if (in_array($answer->answer->label, self::LABELS)) {
                                    $row[$questionId] = $this->addCustomAnswer($answer->answer_text);
                                }
                            } else {
                                $row[$questionId] = '';
                            }

                            if ($questionId === $qId) {
                                break;
                            }
                        }

                        if (!array_key_exists($qId, $row)) {
                            $row[$qId] = '';
                        }
                    }

                    $row = [
                        'id' => $user->id,
                        'name' => $user->nickname,
                        'created_at' => $user->created_at->format('d.m.Y')
                    ] + $row;

                    $this->sheet[] = $row;
                }
            }
        }

//        dd($this->sheet);
        return collect($this->sheet);
    }

    private function addCustomAnswer($answer)
    {
        if (strpos($answer, '{') !== false) {
            return json_decode($answer, true)['year'];
        }
        return $answer;
    }

    private function extendTableHead($questionId)
    {
        $key = array_search($questionId, array_keys($this->sheet[0]));
        $newTitle = $this->sheet[0][$key] . ' Details';

        if (in_array($newTitle, $this->sheet[0])) {
            return;
        }
        array_splice($this->sheet[0], $key + 1, 0, [$newTitle]);
    }
}
