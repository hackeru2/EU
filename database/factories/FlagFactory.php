<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;

use Faker\Generator as Faker;

$factory->define(App\Flag::class, function (Faker $faker) {
        return [
            'name' => $faker->name,
            'abbreviation' => $faker->name,
        ];
    });
