<?php

namespace App\Http\Requests\Tests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTestRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'questions' => 'required',
            'questions.*.question' => 'required',
            'questions.*.sequence' => 'required|int',
            'questions.*.possible_answers' => 'required',
            'questions.*.possible_answers.*.answer' => 'required',
            'questions.*.possible_answers.*.sequence' => 'required',
        ];
    }
}
