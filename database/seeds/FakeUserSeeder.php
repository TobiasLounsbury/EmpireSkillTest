<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class FakeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      //Create some fake users
      $users = [];
      $userRepository = new \App\Repositories\Frontend\Auth\UserRepository();
      $faker = Faker::create();
      foreach (range(0,9) as $index) {
        $users[$index] = $userRepository->create([
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->email,
            'nickname' => $faker->userName,
            'password' => bcrypt('password'),
        ]);

        $users[$index]->confirmed = true;
        $users[$index]->save();
      }


      //Create some fake games

    }
}
