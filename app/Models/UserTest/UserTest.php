<?php

namespace App\Models\UserTest;

use Eloquent;
use Carbon\Carbon;
use App\Models\Test\Test;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UserTest
 *
 * @property int $id
 * @property int $user_id
 * @property int $test_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $passed_at
 * @property Test $test
 * @property Collection|UserTestAnswer[] $answers
 *
 * @package App\Models\UserTest
 * @mixin Eloquent
 */
class UserTest extends Model
{
    protected $dates = ['created_at', 'updated_at', 'passed_at'];

    public function test() : BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function answers() : HasMany
    {
        return $this->hasMany(UserTestAnswer::class);
    }
}
