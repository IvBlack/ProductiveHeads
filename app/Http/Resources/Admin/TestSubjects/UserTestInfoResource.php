<?php

namespace App\Http\Resources\Admin\TestsSubjects;

use App\Models\Test\Test;
use Illuminate\Http\Request;
use App\Models\UserTest\UserTest;
use App\Http\Resources\BasicResource;

/**
 * Class TestInfoResource
 *
 * @package App\Http\Resources
 * @mixin UserTest
 */
class UserTestInfoResource extends BasicResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'passed_at' => $this->passed_at,
            'test' => [
                'id' => $this->test_id,
                'title' => $this->test->title,
                'description' => $this->test->description,
                'questions_count' => $this->test->questions()->count(), //TODO подсчитывать при сохранении
            ],
        ];
    }
}
