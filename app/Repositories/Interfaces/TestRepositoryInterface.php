<?php


namespace App\Repositories\Interfaces;


use App\Models\User;
use App\Models\UserTest\UserTest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TestRepositoryInterface
{
    public function getList() : LengthAwarePaginator;

    public function search(string $search, ?array $exclude = []) : LengthAwarePaginator;

    public function getUserList(User $user) : LengthAwarePaginator;

    public function loadAnswers(UserTest $userTest) : UserTest;
}
