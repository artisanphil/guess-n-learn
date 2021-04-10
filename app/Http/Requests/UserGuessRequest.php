<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Repositories\ObjectRepository;
use Illuminate\Foundation\Http\FormRequest;

class UserGuessRequest extends FormRequest
{
    public function rules()
    {
        $objectRepository = new ObjectRepository();
        $correctSentence = $objectRepository->getSentenceByAttribute($this->choice);

        return [
            'choice' => [
                'required',
                'string',
            ],
            'sentence' => [
                'required',
                'string',
                Rule::in([$correctSentence])
            ]
        ];
    }
}
