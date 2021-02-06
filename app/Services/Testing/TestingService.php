<?php

namespace App\Services\Testing;

use DB;
use Carbon\Carbon;
use App\Models\Test\Question;
use App\Models\UserTest\UserTest;
use Illuminate\Support\Collection;
use App\Models\Test\PossibleAnswer;
use App\Models\UserTest\UserTestAnswer;
use App\Services\Interfaces\TestingServiceInterface;

class TestingService implements TestingServiceInterface
{

    public function answer(UserTest $userTest, Question $question, Collection $answers) : Collection
    {
        DB::beginTransaction();
        $userTestAnswers = collect();
        UserTestAnswer::where([
            'user_test_id' => $userTest->id,
            'question_id' => $question->id,
        ])->delete();
        foreach ($answers as $answer) {
            $userTestAnswers->push(UserTestAnswer::create([
                'user_test_id' => $userTest->id,
                'question_id' => $question->id,
                'answer_id' => $answer->id,
            ]));
        }

        $this->tryToFinish($userTest);

        DB::commit();

        return $userTestAnswers;
    }

    private function tryToFinish(UserTest $userTest)
    {
        $test = $userTest->test;

        $requiredQuestions = $test->questions()->pluck('id');
        $count = $userTest->answers()->whereIn('question_id', $requiredQuestions)->count();
        if ($requiredQuestions->count() === $count) {
            $userTest->passed_at = Carbon::now();
            $userTest->save();
        }
    }
}
