<?php

namespace App\Http\Resources\Admin\Tests;

use Illuminate\Http\Request;
use App\Models\Test\Question;
use App\Http\Resources\BasicResource;

/**
 * Class TestResource
 *
 * @package App\Http\Resources
 * @mixin Question
 */
class QuestionResource extends BasicResource
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
            'sequence' => $this->sequence,
            'answer_ids' => $this->answer_ids ?? [],
            'possible_answers' => PossibleAnswerResource::collection($this->possibleAnswers->sortBy('sequence')),
            'multiple_answers' => $this->multiple_answers,
        ];
    }
}
