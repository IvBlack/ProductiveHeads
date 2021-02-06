<?php

namespace App\Http\Resources\Admin\Tests;

use App\Models\Test\Test;
use Illuminate\Http\Request;
use App\Http\Resources\BasicResource;

/**
 * Class TestInfoResource
 *
 * @package App\Http\Resources
 * @mixin Test
 */
class TestInfoResource extends BasicResource
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
            'title' => $this->title,
            'description' => $this->description,
            'questions_count' => $this->questions()->count(),
        ];
    }
}
