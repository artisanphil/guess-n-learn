<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Repositories\ObjectRepository;
use Illuminate\Foundation\Http\FormRequest;

class SelectionRequest extends FormRequest
{
    public function rules()
    {
        $objectRepository = new ObjectRepository();
        $objectNames = $objectRepository->getObjectNames();

        return [
            'selection' => [
                'required',
                Rule::in($objectNames),
            ],
        ];
    }
}
