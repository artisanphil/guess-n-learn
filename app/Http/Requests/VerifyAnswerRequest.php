<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyAnswerRequest extends FormRequest
{
    public function rules()
    {
        return [
            'chosenAttribute' => [
                'required',
                'string',
            ],
            'answerAttribute' => [
                'string',
            ],
        ];
    }
}