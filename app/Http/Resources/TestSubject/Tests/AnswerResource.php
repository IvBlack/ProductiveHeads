<?php

namespace App\Http\Resources\TestSubject\Tests;

use App\Models\Test\Test;
use App\Models\Test\Question;
use App\Models\UserTest\UserTest;
use App\Models\Test\PossibleAnswer;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserTestResource
 *
 * @package App\Http\Resources\TestSubject\Tests
 * @mixin PossibleAnswer
 */
class AnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'answer' => $this->answer
        ];
    }
}
