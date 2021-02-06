<?php

use App\Models\Test\Question;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Test\Test;
use App\Models\Test\PossibleAnswer;

class TestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $testsCount = 10;
        $questionsCount = 10;
        $answersCount = 3;

        for ($i = 1; $i <= $testsCount; $i++) {
            /** @var Test $test */
            $test = Test::create([
                'title' => $faker->sentence(3, true),
                'description' => $faker->paragraph(3, true),
            ]);
            for ($j = 1; $j <= $questionsCount; $j++) {
                /** @var Question $question */
                $question = Question::create([
                    'test_id' => $test->id,
                    'question' => $faker->sentence(7, true),
                    'description' => $faker->paragraph(3, true),
                ]);
                for ($k = 1; $k <= $answersCount; $k++) {
                    PossibleAnswer::create([
                        'question_id' => $question->id,
                        'answer' => $faker->word(),
                        'description' => $faker->paragraph(3, true),
                    ]);
                }
            }
        }
    }
}
