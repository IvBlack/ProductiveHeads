<?php

namespace App\Models\UserTest;

use Eloquent;
use App\Models\Test\Question;
use App\Models\Test\PossibleAnswer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UserTestAnswer
 *
 * @property int $id
 * @property int $user_test_id
 * @property UserTest $userTest
 * @property int $question_id
 * @property Question $question
 * @property int $answer_id
 * @property PossibleAnswer $answer
 * @package App\Models\UserTest
 * @mixin Eloquent
 */
class UserTestAnswer extends Model
{
    protected $fillable = ['user_test_id', 'question_id', 'answer_id'];

    public function userTest() : BelongsTo
    {
        return $this->belongsTo(UserTest::class);
    }

    public function question() : BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function answer() : BelongsTo
    {
        return $this->belongsTo(PossibleAnswer::class, 'answer_id');
    }
}
