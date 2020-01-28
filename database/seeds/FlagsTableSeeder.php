<?php

use Illuminate\Database\Seeder;

class FlagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(App\Flag::class, 23)->create() ;

        // DB::table('flags')->insert([
        //     'name' => Str::random(10),
        //     'abbreviation' => Str::random(10),
        //     // 'password' => bcrypt('password'),
        // ]);
    }
}
