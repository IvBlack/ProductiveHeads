<?php


namespace App\Services\Interfaces;


use App\Models\Test\Question;
use App\Models\UserTest\UserTest;
use Illuminate\Support\Collection;
use App\Models\Test\PossibleAnswer;
use App\Models\UserTest\UserTestAnswer;

interface TestingServiceInterface
{
    /**
     * @param UserTest $userTest
     * @param Question $question
     * @param Collection|PossibleAnswer[] $answers
     *
     * @return Collection|UserTestAnswer[]
     */
    public function answer(UserTest $userTest, Question $question, Collection $answers) : Collection;
}
