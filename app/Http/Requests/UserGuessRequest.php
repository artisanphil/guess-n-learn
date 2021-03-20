<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserGuessRequest extends FormRequest
{
    public function rules()
    {
        return [
            'choice' => [
                'required',
                'string',
            ],
        ];
    }
}
