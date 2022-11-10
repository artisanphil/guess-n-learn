<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyAttributeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'type' => [
                'required',
                'string',
            ],
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
