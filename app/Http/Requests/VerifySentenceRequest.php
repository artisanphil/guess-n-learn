<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifySentenceRequest extends FormRequest
{
    public function rules()
    {
        return [
            'chosenAttribute' => [
                'required',
                'string',
            ],
            'answerSentence' => [
                'string',
            ],
        ];
    }
}
