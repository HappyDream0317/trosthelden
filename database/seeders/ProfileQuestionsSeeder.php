<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProfileQuestionsSeeder extends Seeder
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

    private function updateProfileQuestions()
    {
        echo 'Updating ProfileQuestions...';
        $FIELD_ID = 0;
        $FIELD_TEXT = 1;
        $FIELD_POSITION = 2;
        $FIELD_ACTIVE = 3;
        $existingIds = [];
        if (($handle = fopen(dirname(__FILE__).'/profile_questions.csv', 'r')) !== false) {
            $i = 0;
            while (($data = fgetcsv($handle, $length = 0, $delimiter = ';')) !== false) {
                if ($i == 0) {
                    $i++;
                    continue;
                }

                $updatedTypeID = \App\ProfileQuestion::updateOrCreate(
                    ['id' => $data[$FIELD_ID]],
                    [
                        'text' => $data[$FIELD_TEXT],
                        'position' => $data[$FIELD_POSITION],
                        'active' => $data[$FIELD_ACTIVE],
                    ]
                )->id;
                array_push($existingIds, $updatedTypeID);
            }
        }
        // Delete types which are not present in CSV file
        \App\Tooltip::whereNotIn('id', $existingIds)->delete();
        echo "done\n";
    }

    public function run()
    {
        echo "Updating ProfileQuestions Seeds...\n";
        $this->updateProfileQuestions();

        $this->verifyDBSetting();
    }
}
