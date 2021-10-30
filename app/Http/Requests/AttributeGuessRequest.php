<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Repositories\ObjectRepository;
use Illuminate\Foundation\Http\FormRequest;

class AttributeGuessRequest extends FormRequest
{
    public function rules()
    {
        return [
            'choice' => [
                'required',
                'string',
            ]
        ];
    }
}
