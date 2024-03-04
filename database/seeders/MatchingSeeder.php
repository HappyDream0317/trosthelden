<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatchingSeeder extends Seeder
{
    private function verifyDBSetting()
    {
        /*
        $verify = (array)DB::select("SELECT IF((SELECT `VARIABLE_VALUE` FROM `performance_schema`.`global_variables`
            WHERE `VARIABLE_NAME` = 'foreign_key_checks') = 'ON', 1, 0) `global.foreign_key_checks`,
            IF((SELECT `VARIABLE_VALUE`
            FROM `performance_schema`.`session_variables`
            WHERE `VARIABLE_NAME` = 'foreign_key_checks') = 'ON', 1, 0) `session.foreign_key_checks`;")[0];

        if (!$verify["global.foreign_key_checks"] || !$verify["session.foreign_key_checks"]) {
            throw new Exception("DB checks failed!");
        }**/
    }

    private function updateTypes()
    {
        echo 'Updating Types...';
        $FIELD_NAME = 3;
        $FIELD_DESCRIPTION = 4;
        $existingIds = [];
        if (($handle = fopen(dirname(__FILE__).'/matching_answer_types.csv', 'r')) !== false) {
            $i = 0;
            while (($data = fgetcsv($handle, $length = 0, $delimiter = ';')) !== false) {
                if ($i == 0) {
                    $i++;
                    continue;
                }
                $updatedTypeID = \App\MatchingAnswerType::updateOrCreate(
                    ['name' => $data[$FIELD_NAME]],
                    ['description' => $data[$FIELD_DESCRIPTION]]
                )->id;
                array_push($existingIds, $updatedTypeID);
            }
        }
        // Delete types which are not present in CSV file
        \App\MatchingAnswerType::whereNotIn('id', $existingIds)->delete();
        echo "done\n";
    }

    private function updateDimensionsDef()
    {
        echo 'Updating Dimension Definitions...';
        $FIELD_ID = 0;
        $FIELD_DIMENSION = 3;
        $FIELD_ANSWER_ID_OWN = 4;
        $FIELD_ANSWER_ID_PARTNER = 5;
        $FIELD_VALUE_CODE = 6;
        $FIELD_WEIGHT = 7;
        $FIELD_EXCLUSION = 8;
        $existingIds = [];
        if (($handle = fopen(dirname(__FILE__).'/matching_cluster_dimensions_def.csv', 'r')) !== false) {
            $i = 0;
            while (($data = fgetcsv($handle, $length = 0, $delimiter = ';')) !== false) {
                if ($i == 0) {
                    $i++;
                    continue;
                }
                $updatedTypeID = \App\MatchingClusterDimensionsDefinition::updateOrCreate(
                    ['id' => $data[$FIELD_ID]],
                    [
                        'dimension' => empty($data[$FIELD_DIMENSION]) || $data[$FIELD_DIMENSION] == 'NULL' ? null : (int) $data[$FIELD_DIMENSION],
                        'answer_id_own' => $data[$FIELD_ANSWER_ID_OWN],
                        'answer_id_partner' => $data[$FIELD_ANSWER_ID_PARTNER],
                        'valuecode' => empty($data[$FIELD_VALUE_CODE]) || $data[$FIELD_VALUE_CODE] == 'NULL' ? null : (int) $data[$FIELD_VALUE_CODE],
                        'weight' => empty($data[$FIELD_WEIGHT]) || $data[$FIELD_WEIGHT] == 'NULL' ? null : (int) $data[$FIELD_WEIGHT],
                        'exclusion' => empty($data[$FIELD_EXCLUSION]) || $data[$FIELD_EXCLUSION] == 'NULL' ? null : (int) $data[$FIELD_EXCLUSION],
                    ]
                )->id;
                array_push($existingIds, $updatedTypeID);
            }
        }
        // Delete types which are not present in CSV file
        \App\MatchingAnswerType::whereNotIn('id', $existingIds)->delete();
        echo "done\n";
    }

    private function updateRankingDef()
    {
        echo 'Updating Ranking Definitions...';
        $FIELD_ID = 0;
        $FIELD_RANK = 1;
        $FIELD_DIM_1 = 2;
        $FIELD_DIM_2 = 3;
        $FIELD_DIM_3 = 4;
        $FIELD_DIM_4 = 5;
        $FIELD_DIM_5 = 6;
        $existingIds = [];
        if (($handle = fopen(dirname(__FILE__).'/matching_cluster_ranking_def.csv', 'r')) !== false) {
            $i = 0;
            while (($data = fgetcsv($handle, $length = 0, $delimiter = ';')) !== false) {
                if ($i == 0) {
                    $i++;
                    continue;
                }
                $updatedTypeID = \App\MatchingClusterRankingDefinition::updateOrCreate(
                    ['id' => $data[$FIELD_ID]],
                    [
                        'rank' => $data[$FIELD_RANK],
                        'dim1' => $data[$FIELD_DIM_1],
                        'dim2' => $data[$FIELD_DIM_2],
                        'dim3' => $data[$FIELD_DIM_3],
                        'dim4' => $data[$FIELD_DIM_4],
                        'dim5' => $data[$FIELD_DIM_5],
                    ]
                )->id;
                array_push($existingIds, $updatedTypeID);
            }
        }
        // Delete types which are not present in CSV file
        \App\MatchingAnswerType::whereNotIn('id', $existingIds)->delete();
        echo "done\n";
    }

    private function updateQuestions()
    {
        echo 'Updating Questions...';
        $FIELD_ID = 0;
        $FIELD_PARENT_ID = 3;
        $FIELD_DEPENDING_ON = 4;
        $FIELD_CODE = 5;
        $FIELD_QUESTION = 6;
        $FIELD_LABEL = 7;
        $FIELD_ANSWER_TYPE = 8;
        $FIELD_OBLIGATORY = 9;
        $FIELD_CONDITIONS_RELATION_ID = 10;
        $FIELD_POSITION = 11;
        $FIELD_TOOLTIP_TITLE = 12;
        $FIELD_TOOLTIP_CONTENT = 13;

        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // TODO: :-(
        if (($handle = fopen(dirname(__FILE__).'/matching_questions.csv', 'r')) !== false) {
            $i = 0;
            while (($data = fgetcsv($handle, $length = 0, $delimiter = ';')) !== false) {
                if ($i == 0) {
                    $i++;
                    continue;
                }
                \App\MatchingQuestion::updateOrCreate(
                    ['id' => $data[$FIELD_ID]],
                    ['parent_id' => $data[$FIELD_PARENT_ID] ? $data[$FIELD_PARENT_ID] : null,
                        'depending_on_answer_id' => $data[$FIELD_DEPENDING_ON] ? $data[$FIELD_DEPENDING_ON] : null,
                        'code' => $data[$FIELD_CODE],
                        'question' => $data[$FIELD_QUESTION],
                        'label' => $data[$FIELD_LABEL],
                        'answer_type_id' => $data[$FIELD_ANSWER_TYPE],
                        'obligatory' => $data[$FIELD_OBLIGATORY] ? $data[$FIELD_OBLIGATORY] : false,
                        'conditions_relation_id' => $data[$FIELD_CONDITIONS_RELATION_ID] ? $data[$FIELD_CONDITIONS_RELATION_ID] : null,
                        'position' => $data[$FIELD_POSITION],
                        'tooltip_title' => $data[$FIELD_TOOLTIP_TITLE] ? $data[$FIELD_TOOLTIP_TITLE] : null,
                        'tooltip_content' => $data[$FIELD_TOOLTIP_CONTENT] ? $data[$FIELD_TOOLTIP_CONTENT] : null,
                    ]
                );
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        echo "done\n";
    }

    private function updateAnswers()
    {
        echo 'Updating Answers...';
        $FIELD_ID = 0;
        $FIELD_QUESTION_ID = 3;
        $FIELD_PARENT_ID = 4;
        $FIELD_DEPENDING_ON = 5;
        $FIELD_CODE = 6;
        $FIELD_ANSWER = 7;
        $FIELD_LABEL = 8;
        $FIELD_ADDITIONAL_TEXT = 9;
        $FIELD_DESCRIPTION = 10;
        $FIELD_DIFFERENT_ANSWER_TYPE = 11;
        $FIELD_OBLIGATION = 12;
        $FIELD_NUMERICAL_MIN = 13;
        $FIELD_NUMERICAL_MAX = 14;
        $FIELD_TERMINATION_CONDITION = 15;
        $FIELD_NEXT_QUESTION_ID = 16;
        $FIELD_CONDITIONS_RELATION_ID = 17;
        $FIELD_POSITION = 18;
        $FIELD_VALIDATION_ID = 19;
        $FIELD_TOOLTIP_TITLE = 20;
        $FIELD_TOOLTIP_CONTENT = 21;

        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // TODO: :-(
        if (($handle = fopen(dirname(__FILE__).'/matching_answers.csv', 'r')) !== false) {
            $i = 0;
            while (($data = fgetcsv($handle, $length = 0, $delimiter = ';')) !== false) {
                if ($i == 0) {
                    $i++;
                    continue;
                }
                \App\MatchingAnswer::updateOrCreate(
                    ['id' => $data[$FIELD_ID]],
                    ['question_id' => $data[$FIELD_QUESTION_ID] ? $data[$FIELD_QUESTION_ID] : null,
                        'parent_id' => $data[$FIELD_PARENT_ID] ? $data[$FIELD_PARENT_ID] : null,
                        'depending_on_answer_id' => $data[$FIELD_DEPENDING_ON] ? $data[$FIELD_DEPENDING_ON] : null,
                        'code' => $data[$FIELD_CODE],
                        'answer' => $data[$FIELD_ANSWER],
                        'label' => $data[$FIELD_LABEL],
                        'additional_text' => $data[$FIELD_ADDITIONAL_TEXT] ? $data[$FIELD_ADDITIONAL_TEXT] : false,
                        'description' => $data[$FIELD_DESCRIPTION],
                        'different_answer_type_id' => $data[$FIELD_DIFFERENT_ANSWER_TYPE] ? $data[$FIELD_DIFFERENT_ANSWER_TYPE] : null,
                        'obligatory' => $data[$FIELD_OBLIGATION] ? $data[$FIELD_OBLIGATION] : false,
                        'numeric_min' => is_numeric($data[$FIELD_NUMERICAL_MIN]) ? $data[$FIELD_NUMERICAL_MIN] : null,
                        'numeric_max' => is_numeric($data[$FIELD_NUMERICAL_MAX]) ? $data[$FIELD_NUMERICAL_MAX] : null,
                        'termination_condition' => $data[$FIELD_TERMINATION_CONDITION] ? $data[$FIELD_TERMINATION_CONDITION] : false,
                        'next_question_id' => $data[$FIELD_NEXT_QUESTION_ID] ? $data[$FIELD_NEXT_QUESTION_ID] : null,
                        'conditions_relation_id' => $data[$FIELD_CONDITIONS_RELATION_ID] ? $data[$FIELD_CONDITIONS_RELATION_ID] : null,
                        'position' => $data[$FIELD_POSITION] ? $data[$FIELD_POSITION] : 0,
                        'matching_answer_validation_id' => $data[$FIELD_VALIDATION_ID] ? $data[$FIELD_VALIDATION_ID] : null,
                        'tooltip_title' => $data[$FIELD_TOOLTIP_TITLE] ? $data[$FIELD_TOOLTIP_TITLE] : null,
                        'tooltip_content' => $data[$FIELD_TOOLTIP_CONTENT] ? $data[$FIELD_TOOLTIP_CONTENT] : null,
                    ]
                );
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        echo "done\n";
    }

    private function updateConditions()
    {
        echo 'Updating Conditions...';
        $FIELD_ID = 0;
        $FIELD_RELATION_ID = 3;
        $FIELD_RELATED_QUESTION_ID = 4;
        $FIELD_RELATED_ANSWER_ID = 5;
        $FIELD_VISIBLE_IF_TRUE = 6;
        $FIELD_BEFORE = 7;
        $FIELD_OPERATOR_SEVERAL_COND = 8;
        $FIELD_ANSWER_ID = 9;
        $FIELD_OPERATOR = 10;
        $FIELD_ANSWER_CONTENT = 11;
        $FIELD_AFTER = 12;
        $FIELD_POSITION = 13;
        $FIELD_NEXT_QUESTION_CASE = 14;

        if (($handle = fopen(dirname(__FILE__).'/matching_question_answer_conditions.csv', 'r')) !== false) {
            $i = 0;
            while (($data = fgetcsv($handle, $length = 0, $delimiter = ';')) !== false) {
                if (empty($data[$FIELD_ID])) {
                    continue;
                }
                if ($i == 0) {
                    $i++;
                    continue;
                }
                \App\MatchingQuestionAnswerCondition::updateOrCreate(
                    ['id' => $data[$FIELD_ID]],
                    ['relation_id' => $data[$FIELD_RELATION_ID],
                        'related_question_id' => $data[$FIELD_RELATED_QUESTION_ID] ? $data[$FIELD_RELATED_QUESTION_ID] : null,
                        'related_answer_id' => $data[$FIELD_RELATED_ANSWER_ID] ? $data[$FIELD_RELATED_ANSWER_ID] : null,
                        'visible_if_true' => $data[$FIELD_VISIBLE_IF_TRUE],
                        'before' => $data[$FIELD_BEFORE],
                        'operator_several_conditions' => $data[$FIELD_OPERATOR_SEVERAL_COND],
                        'answer_id' => $data[$FIELD_ANSWER_ID] ? $data[$FIELD_ANSWER_ID] : null,
                        'operator' => $data[$FIELD_OPERATOR],
                        'answer_content' => $data[$FIELD_ANSWER_CONTENT],
                        'after' => $data[$FIELD_AFTER],
                        'position' => $data[$FIELD_POSITION] ? $data[$FIELD_POSITION] : null,
                        'next_question_id_if_false' => $data[$FIELD_NEXT_QUESTION_CASE] ? $data[$FIELD_NEXT_QUESTION_CASE] : null,
                    ]
                );
            }
        }
        echo "done\n";
    }

    private function updateSteps()
    {
        echo 'Updating Question Steps...';
        $FIELD_QUESTION_ID = 3;
        $FIELD_STEP_NO = 4;
        $FIELD_CONTENT_BEFORE = 5;
        $FIELD_CONTENT_AFTER = 6;

        if (($handle = fopen(dirname(__FILE__).'/matching_question_steps.csv', 'r')) !== false) {
            $i = 0;
            while (($data = fgetcsv($handle, $length = 0, $delimiter = ';')) !== false) {
                if ($i == 0) {
                    $i++;
                    continue;
                }
                \App\MatchingQuestionStepContent::updateOrCreate(
                    ['question_id' => $data[$FIELD_QUESTION_ID]],
                    [
                        'step_no' => $data[$FIELD_STEP_NO],
                        'content_before' => $data[$FIELD_CONTENT_BEFORE],
                        'content_after' => $data[$FIELD_CONTENT_AFTER],
                    ]
                );
            }
        }
        echo "done\n";
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "Updating Matching Seeds...\n";
        $this->updateTypes();
        $this->updateQuestions();
        $this->updateAnswers();
        $this->updateConditions();
        $this->updateSteps();

        $this->updateRankingDef();
        $this->updateDimensionsDef();

        $this->verifyDBSetting();
    }
}
