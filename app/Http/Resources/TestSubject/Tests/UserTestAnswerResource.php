<?php

namespace App\Http\Resources\TestSubject\Tests;

use App\Models\UserTest\UserTestAnswer;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserTestResource
 *
 * @package App\Http\Resources\TestSubject\Tests
 * @mixin UserTestAnswer
 */
class UserTestAnswerResource extends JsonResource
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
            'answer_id' => $this->answer_id
        ];
    }
}
