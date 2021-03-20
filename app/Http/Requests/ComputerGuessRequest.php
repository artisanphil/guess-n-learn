<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComputerGuessRequest extends FormRequest
{
    public function rules()
    {
        return [
            'choice' => [
                'required',
                'string',
            ],
            'correct' => [
                'required',
                'boolean'
            ]
        ];
    }
}
