<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Profile;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Profile::class, function (Faker $faker) {
    return [
        'experties' => $faker->name .' , ' .$faker->title .','. $faker->name ,
        'keywords' => $faker->name .' , ' .$faker->title .','. $faker->name ,
        'user_id' =>User::get()->last()->id,
       // 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //'remember_token' => Str::random(10),
            // $table->string('email')->unique();
    ];
});
