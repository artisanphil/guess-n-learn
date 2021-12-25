<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetCorrectSentenceRequest extends FormRequest
{
    public function rules()
    {
        return [
            'chosenAttribute' => [
                'required',
                'string',
            ],
        ];
    }
}
