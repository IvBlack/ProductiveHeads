<?php

namespace App\Models\Test;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Test\PossibleAnswer
 *
 * @mixin Eloquent
 *
 * @property int $id
 * @property string $answer
 * @property int sequence
 * @property-read Question $question
 * @property int $question_id
 */
class PossibleAnswer extends Model
{
    protected $fillable = [
        'question_id',
        'answer',
    ];

    public function question() : BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
