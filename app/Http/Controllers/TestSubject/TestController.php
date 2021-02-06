<?php

namespace App\Http\Controllers\TestSubject;

use App\Models\Test\Question;
use Illuminate\Http\JsonResponse;
use App\Models\UserTest\UserTest;
use App\Models\Test\PossibleAnswer;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnswerRequest;
use App\Services\Interfaces\TestingServiceInterface;
use App\Repositories\Interfaces\TestRepositoryInterface;
use App\Http\Resources\TestSubject\Tests\UserTestResource;
use App\Http\Resources\TestSubject\Tests\UserTestInfoResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Resources\TestSubject\Tests\UserTestAnswerResource;

class TestController extends Controller
{
    /**
     * @param TestRepositoryInterface $testRepository
     *
     * @return AnonymousResourceCollection
     */
    public function index(TestRepositoryInterface $testRepository)
    {
        return UserTestInfoResource::collection($testRepository->getUserList(auth()->user()));
    }

    public function show(UserTest $userTest, TestRepositoryInterface $testRepository)
    {
        return UserTestResource::make($testRepository->loadAnswers($userTest));
    }

    public function answer(
        UserTest $userTest,
        Question $question,
        TestingServiceInterface $testingService,
        AnswerRequest $request
    )
    {
        $answers = PossibleAnswer::whereIn('id', $request->input('answer_ids'))->get();
        foreach ($answers as $answer) {
            if (
                +$userTest->test_id !== +$question->test_id
                && +$answer->question_id !== +$question->id
            ) {
                return response()->json(['message' => 'Неверная связь тест-вопрос-ответ'], 401);
            }
        }

        if ($answers->count() > 1 && !$question->multiple_answers) {
            return response()->json(['message' => 'Вопрос не предполагает множественного ответа'], 401);
        }

        $userTestAnswers = $testingService->answer($userTest, $question, $answers);

        return UserTestAnswerResource::collection($userTestAnswers);
    }
}

