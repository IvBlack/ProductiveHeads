<?php

namespace App\Http\Resources\TestSubject\Tests;

use App\Models\UserTest\UserTest;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserTestResource
 *
 * @package App\Http\Resources\TestSubject\Tests
 * @mixin UserTest
 */
class UserTestResource extends JsonResource
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
            'passed_at' => $this->passed_at,
            'test' => TestResource::make($this->test),
        ];
    }
}
