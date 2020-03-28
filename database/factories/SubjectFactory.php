<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Subject;
use Faker\Generator as Faker;

$factory->define(Subject::class, function (Faker $faker) {
    return [
            'title' => 'Security',
            'headers' => ['Crime Types' , 'Technology'],
    ];
});
