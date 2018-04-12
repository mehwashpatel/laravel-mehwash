<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
class UsersTableSeeder extends Seeder{
    public function run()
    {
        $faker = Faker::create();
        foreach(range(1, 10) as $index)
        {
            \App\User::create([
                'name' => $faker->name(),
                'email' => $faker->email(),
                'password' => \Hash::make('secret'),
                'api_token' => str_random(60)
            ]);
        }
    }
}
