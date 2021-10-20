<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ObjectGuessRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
            ],
        ];
    }
}
