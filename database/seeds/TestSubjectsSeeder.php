<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TestSubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($k = 1; $k <= 10; $k++) {
            /*  insert users   */
            $user = User::create([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'middle_name' => $faker->firstName,
                'phone' => $faker->phoneNumber,
                'email' => $faker->email,
                'menuroles' => 'test-subject',
                'test_subject' => true,
            ]);
            $user->assignRole('test-subject');
        }

        $user = User::create([
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'middle_name' => 'Иванович',
            'phone' => '+79111111111',
            'email' => $faker->email,
            'auth_code' => '12345678',
            'menuroles' => 'test-subject',
            'test_subject' => true,
        ]);
        $user->assignRole('test-subject');
    }
}
