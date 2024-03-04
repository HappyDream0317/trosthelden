<?php

namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\GroupCategory;
use Faker\Generator as Faker;

$factory->define(GroupCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->userName,
        'description' => $faker->text,
        'max_size' => 20,
    ];
});
