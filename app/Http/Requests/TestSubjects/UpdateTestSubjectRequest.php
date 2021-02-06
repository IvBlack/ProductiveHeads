<?php

namespace App\Http\Requests\TestSubjects;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTestSubjectRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => "required|regex:/^\+7\d{10}$/i|unique:users,phone," . $this->testSubject->id,
            'email' => 'sometimes|email',
        ];
    }
}
