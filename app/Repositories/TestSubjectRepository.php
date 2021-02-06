<?php


namespace App\Repositories;

use DB;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TestSubjectRepository implements Interfaces\TestSubjectRepositoryInterface
{
    public function getList(string $search = null) : LengthAwarePaginator
    {
        return User::testSubject()
            ->when($search, function (Builder $query) use ($search) {
                $search = '%' . strtolower($search) . '%';
                $query->where(DB::raw('CONCAT(LOWER(last_name), \' \', LOWER(first_name), \' \', LOWER(middle_name))'), 'like', $search);
            })
            ->paginate(15);
    }
}
