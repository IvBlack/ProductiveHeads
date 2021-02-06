<?php

namespace App\Http\Resources\TestSubject\Tests;

use App\Models\Test\Test;
use Illuminate\Http\Request;
use App\Models\Test\Question;
use App\Models\UserTest\UserTest;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserTestResource
 *
 * @package App\Http\Resources\TestSubject\Tests
 * @mixin Question
 */
class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'question' => $this->question,
            'description' => $this->description,
            'answer_ids' => $this->answer_ids ?? [],
            'possible_answers' => AnswerResource::collection($this->possibleAnswers),
            'multiple_answers' => $this->multiple_answers,
        ];
    }
}
