<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    private function verifyDBSetting()
    {
    }

    private function updateFAQQuestions()
    {
        echo 'Updating FAQ...';

        $FIELD_ID = 0;
        $FIELD_POSITION = 1;
        $FIELD_TOPIC = 2;
        $FIELD_QUESTION = 3;
        $FIELD_PUBLIC = 4;
        $FIELD_ANSWER = 5;
        $existingIds = [];
        if (($handle = fopen(dirname(__FILE__).'/faq.csv', 'r')) !== false) {
            $i = 0;
            while (($data = fgetcsv($handle, $length = 0, $delimiter = ';')) !== false) {
                if ($i == 0) {
                    $i++;
                    continue;
                }

                $updatedTypeID = \App\FaqQuestion::updateOrCreate(
                    ['id' => $data[$FIELD_ID]],
                    [
                        'position' => $data[$FIELD_POSITION],
                        'topic' => $data[$FIELD_TOPIC],
                        'question' => $data[$FIELD_QUESTION],
                        'public' => $data[$FIELD_PUBLIC],
                        'answer' => $data[$FIELD_ANSWER],
                    ],
                )->id;
                array_push($existingIds, $updatedTypeID);
            }
        }
        // Delete types which are not present in CSV file
        \App\FaqQuestion::whereNotIn('id', $existingIds)->delete();
        echo "done\n";
    }

    public function run()
    {
        echo "Updating FAQ Seeds...\n";
        $this->updateFAQQuestions();

        $this->verifyDBSetting();
    }
}
