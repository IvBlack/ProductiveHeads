<?php

namespace App\Models\Test;

use Eloquent;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Test\Question
 *
 * @mixin Eloquent
 *
 * @property int id
 * @property string $question
 * @property string $description
 * @property int $sequence
 * @property int $test_id
 * @property bool $multiple_answers
 * @property-read Test $test
 * @property-read Collection|PossibleAnswer[] $possibleAnswers
 */
class Question extends Model
{
    protected $fillable = [
        'test_id',
        'question',
        'description',
    ];

    public $casts = [
        'sequence' => 'integer',
        'test_id' => 'integer',
        'multiple_answers' => 'boolean'
    ];

    public function test() : BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function possibleAnswers() : HasMany
    {
        return $this->hasMany(PossibleAnswer::class);
    }
}
