<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
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

    private function updateGroupCategories()
    {
        echo 'Updating Categories...';

        $FIELD_ID = 0;
        $FIELD_NAME = 1;
        $FIELD_DESCRIPTION = 2;
        $FIELD_ICON = 3;
        $FIELD_MAX_SIZE = 4;
        $existingIds = [];
        if (($handle = fopen(dirname(__FILE__).'/group_categories.csv', 'r')) !== false) {
            $i = 0;
            while (($data = fgetcsv($handle, $length = 0, $delimiter = ';')) !== false) {
                if ($i == 0) {
                    $i++;
                    continue;
                }

                $updatedTypeID = \App\GroupCategory::updateOrCreate(
                    ['id' => $data[$FIELD_ID]],
                    [
                        'name' => $data[$FIELD_NAME],
                        'description' => $data[$FIELD_DESCRIPTION],
                        'icon' => $data[$FIELD_ICON],
                        'max_size' => $data[$FIELD_MAX_SIZE],
                    ],
                )->id;
                array_push($existingIds, $updatedTypeID);
            }
        }
        // Delete types which are not present in CSV file
        \App\GroupCategory::whereNotIn('id', $existingIds)->delete();
        echo "done\n";
    }

    private function updateGroups()
    {
        echo 'Updating Groups...';

        $FIELD_ID = 0;
        $FIELD_CATEGORY_ID = 1;
        $FIELD_NAME = 2;
        $FIELD_OPEN = 3;
        $existingIds = [];
        if (($handle = fopen(dirname(__FILE__).'/groups.csv', 'r')) !== false) {
            $i = 0;
            while (($data = fgetcsv($handle, $length = 0, $delimiter = ';')) !== false) {
                if ($i == 0) {
                    $i++;
                    continue;
                }
                var_dump($data);

                $updatedTypeID = \App\Group::updateOrCreate(
                    ['id' => $data[$FIELD_ID]],
                    [
                        'category_id' => $data[$FIELD_CATEGORY_ID],
                        'name' => $data[$FIELD_NAME],
                        'open' => $data[$FIELD_OPEN],
                    ]
                )->id;
                array_push($existingIds, $updatedTypeID);
            }
        }
        // Delete types which are not present in CSV file
        \App\Group::whereNotIn('id', $existingIds)->delete();
        echo "done\n";
    }

    public function run()
    {
        echo "Updating GroupSeeds Seeds...\n";
        $this->updateGroupCategories();
        $this->updateGroups();

        $this->verifyDBSetting();
    }
}
