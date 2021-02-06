<?php

namespace App\Models\Test;

use Eloquent;
use App\Models\User;
use App\Models\UserTest\UserTest;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Test
 *
 * @package App\Models\Test
 * @mixin Eloquent
 *
 * @property int id
 * @property string $title
 * @property string $description
 * @property int $sequence
 * @property-read Collection|Question[] $questions
 * @property-read Collection|User[] $testSubjects
 *
 */
class Test extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
    ];

    public function questions() : HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_tests', 'test_id', 'user_id');
    }

    public function userTests() : HasMany
    {
        return $this->hasMany(UserTest::class);
    }
}
