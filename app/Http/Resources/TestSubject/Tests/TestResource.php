<?php

namespace App\Http\Resources\TestSubject\Tests;

use App\Models\Test\Test;
use App\Models\UserTest\UserTest;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserTestResource
 *
 * @package App\Http\Resources\TestSubject\Tests
 * @mixin Test
 */
class TestResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'questions' => QuestionResource::collection($this->questions),
        ];
    }
}
