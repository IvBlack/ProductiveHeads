<?php

namespace App\Http\Resources\Admin\TestsSubjects;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\BasicResource;

/**
 * Class TestSubjectResource
 *
 * @package App\Http\Resources\TestSubjects
 * @mixin User
 */
class TestSubjectResource extends BasicResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'auth_code' => $this->auth_code,
            'tests' => UserTestInfoResource::collection($this->userTests()->with('test')->get()),
        ];
    }
}
