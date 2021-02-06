<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\Invite;
use App\Models\UserTest\UserTest;
use App\Http\Controllers\Controller;
use App\Http\Requests\TestSubjects\AddTestsRequest;
use App\Services\Interfaces\TestSubjectEditorInterface;
use App\Repositories\Interfaces\TestRepositoryInterface;
use App\Http\Resources\Admin\Tests\UserTestResultResource;
use App\Http\Resources\Admin\TestsSubjects\TestSubjectResource;
use App\Http\Requests\TestSubjects\CreateTestSubjectRequest;
use App\Http\Requests\TestSubjects\UpdateTestSubjectRequest;
use App\Repositories\Interfaces\TestSubjectRepositoryInterface;

class TestSubjectController extends Controller
{
    public function index(TestSubjectRepositoryInterface $testSubjectRepository, Request $request)
    {
        return TestSubjectResource::collection($testSubjectRepository->getList($request->input('search')));
    }

    public function show(User $testSubject)
    {
        return ['test_subject' => TestSubjectResource::make($testSubject)];
    }

    public function create(CreateTestSubjectRequest $request, TestSubjectEditorInterface $testSubjectEditor)
    {
        $testSubject = $testSubjectEditor->create($request->only([
            'first_name',
            'last_name',
            'middle_name',
            'phone',
            'email',
        ]));

        return TestSubjectResource::make($testSubject);
    }

    public function update(UpdateTestSubjectRequest $request, TestSubjectEditorInterface $testSubjectEditor, User $testSubject)
    {
        $testSubjectEditor->update($testSubject, $request->only([
            'first_name',
            'last_name',
            'middle_name',
            'phone',
            'email',
        ]));

        return TestSubjectResource::make($testSubject);
    }

    public function generateCode(TestSubjectEditorInterface $testSubjectEditor, User $testSubject)
    {
        $testSubjectEditor->generateCode($testSubject);

        return ['test_subject' => TestSubjectResource::make($testSubject)];
    }

    public function delete(User $testSubject, TestSubjectEditorInterface $testSubjectEditor)
    {
        $testSubjectEditor->delete($testSubject);
        return true;
    }

    public function addTests(User $testSubject, TestSubjectEditorInterface $testSubjectEditor, AddTestsRequest $request)
    {
        $testSubjectEditor->attachTest($testSubject, $request->input('test_ids'));

        return ['test_subject' => TestSubjectResource::make($testSubject)];
    }

    public function deleteTest(User $testSubject, TestSubjectEditorInterface $testSubjectEditor, UserTest $userTest)
    {
        $testSubjectEditor->detachTest($testSubject, $userTest);

        return ['test_subject' => TestSubjectResource::make($testSubject)];
    }

    public function showResult(User $testSubject, UserTest $userTest, TestRepositoryInterface $testRepository)
    {
        if (
            +$testSubject->id !== +$userTest->user_id
        ) {
            return response()->json(['message' => 'Неверная связь тест-тестируемый'], 401);
        }

        return UserTestResultResource::make($testRepository->loadAnswers($userTest));
    }

    public function sendInvite(User $testSubject)
    {
        $testSubject->notifyNow(new Invite());

        return response()->json(['success' => true]);
    }
}
