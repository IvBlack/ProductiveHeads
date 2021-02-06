<?php


namespace App\Repositories;


use App\Models\User;
use App\Models\Test\Test;
use App\Models\Test\Question;
use App\Models\UserTest\UserTest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TestRepository implements Interfaces\TestRepositoryInterface
{
    public function getList() : LengthAwarePaginator
    {
        return Test::orderBy('sequence')->paginate();
    }

    public function search(?string $search, ?array $exclude = []) : LengthAwarePaginator
    {
        return Test::query()
            ->orderBy('sequence')
            ->when($search, function (Builder $query, $search) {
                $query->where(\DB::raw('LOWER(title)'), 'like', '%' . strtolower($search) . '%');
            })
            ->when($exclude, function (Builder $query, $exclude) {
                $query->whereNotIn('id', $exclude);
            })
            ->paginate();
    }

    public function getUserList(User $user) : LengthAwarePaginator
    {
        return $user->userTests()->with('test')->paginate();
    }

    public function loadAnswers(UserTest $userTest) : UserTest
    {
        $userTest->load(['test.questions' => function (HasMany $query) {
            $query
                ->orderBy('sequence')
                ->with(['possibleAnswers' => function (HasMany $query) {
                    $query
                        ->orderBy('sequence');
                }])
                ->orderBy('sequence');
        }, 'answers']);

        $answers = $userTest->answers->groupBy('question_id');
        $userTest->test->questions->each(function (Question $question) use ($answers) {
            $question->answer_ids = $answers->offsetExists($question->id) ? $answers[$question->id]->pluck('answer_id') : null;
        });

        return $userTest;
    }
}
