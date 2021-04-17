<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Repositories\SentenceRepository;
use Illuminate\Foundation\Http\FormRequest;

class UserGuessRequest extends FormRequest
{
    public function rules()
    {
        $sentenceRepository = new SentenceRepository();
        $correctSentence = $sentenceRepository->getSentenceByAttribute($this->choice);

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
