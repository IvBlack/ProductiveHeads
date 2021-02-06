<?php

namespace App\Services\Interfaces;

use App\Models\User;
use App\Models\UserTest\UserTest;

interface TestSubjectEditorInterface
{
    public function create(array $params) : User;

    public function update(User $user, array $params) : User;

    public function delete(User $user) : bool;

    public function generateCode(User $user) : self;

    public function attachTest(User $user, array $ids) : User;

    public function detachTest(User $user, UserTest $userTest) : User;
}
