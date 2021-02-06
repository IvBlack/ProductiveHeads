<?php


namespace App\Repositories\Interfaces;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TestSubjectRepositoryInterface
{
    public function getList(string $search = null) : LengthAwarePaginator;
}
