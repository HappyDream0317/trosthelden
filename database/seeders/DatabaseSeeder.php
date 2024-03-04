<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MatchingSeeder::class);
        $this->call(TooltipSeeder::class);
        $this->call(ProfileQuestionsSeeder::class);
        // $this->call(GroupSeeder::class);
        $this->call(ValidationSeeder::class);
        $this->call(FaqSeeder::class);
    }
}
