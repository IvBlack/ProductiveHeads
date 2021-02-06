<?php

namespace App\Http\Resources\Admin\Tests;

use Illuminate\Http\Request;
use App\Models\Test\PossibleAnswer;
use App\Http\Resources\BasicResource;

/**
 * Class TestResource
 *
 * @package App\Http\Resources
 * @mixin PossibleAnswer
 */
class PossibleAnswerResource extends BasicResource
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
            'question' => $this->question,
            'answer' => $this->answer,
            'sequence' => $this->sequence,
        ];
    }
}
