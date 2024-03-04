<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BooteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $boot_categories = factory(App\GroupCategory::class, 3)
            ->create()
            ->each(function ($boot_category) {
                $boot_category->boote()->save(factory(App\Group::class)->make());
            });
    }
}
