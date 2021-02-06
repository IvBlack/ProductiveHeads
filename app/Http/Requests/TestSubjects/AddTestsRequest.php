<?php

namespace App\Http\Requests\TestSubjects;

use Illuminate\Foundation\Http\FormRequest;

class AddTestsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'test_ids' => 'required',
            'test_ids.*' => 'exists:tests,id',
        ];
    }
}
