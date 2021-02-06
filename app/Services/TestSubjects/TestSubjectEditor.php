<?php

namespace App\Models\TestSubjects;

use Str;
use App\Models\User;
use App\Models\UserTest\UserTest;
use App\Services\Interfaces\TestSubjectEditorInterface;

class TestSubjectEditor implements TestSubjectEditorInterface
{
    public function create(array $params) : User
    {
        $user = User::make($params);
        $user->menuroles = 'test-subject';
        $user->test_subject = true;
        $user->save();
        $user->assignRole('test-subject');
        return $user;
    }

    public function update(User $testSubject, array $params) : User
    {
        $testSubject->first_name = $params['first_name'] ?? $testSubject->first_name;
        $testSubject->last_name = $params['last_name'] ?? $testSubject->last_name;
        $testSubject->middle_name = $params['middle_name'] ?? $testSubject->middle_name;
        $testSubject->phone = $params['phone'] ?? $testSubject->phone;
        $testSubject->email = $params['email'] ?? $testSubject->email;

        $testSubject->save();

        return $testSubject;
    }

    public function delete(User $testSubject) : bool
    {
        return $testSubject->delete();
    }

    public function generateCode(User $testSubject) : self
    {
        do {
            $code = Str::random(8);
            $duplicate = User::where('auth_code', $code)->exists();
        } while ($duplicate);

        $testSubject->auth_code = $code;
        $testSubject->save();

        return $this;
    }

    public function attachTest(User $testSubject, array $ids) : User
    {
        foreach ($ids as $id) {
            $testSubject->tests()->attach($id);
        }

        return $testSubject;
    }

    public function detachTest(User $testSubject, UserTest $userTest) : User
    {
        if (+$userTest->user_id !== +$testSubject->id) {
            throw new \Exception('Пользователь не прикреплен к этому тесту');
        }
        if (
        !is_null($userTest->passed_at)
        ) {
            throw new \Exception('Нельзя удалить пройденый тест');
        }

        $userTest->delete();

        return $testSubject;
    }
}
