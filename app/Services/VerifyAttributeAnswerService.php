<?php

namespace App\Services;

use App\Constants\QuestionType;
use App\Models\Attribute;
use App\Repositories\AttributeRepository;
use App\Structs\AttributeAnswerStruct;
use Illuminate\Support\Facades\Session;

class VerifyAttributeAnswerService
{
    public function handle(AttributeAnswerStruct $answer, string $type): bool
    {
        $learnLanguage = Session::get('learn-language', 'en');
        $languageShort = substr($learnLanguage, 0, 2);

        $answerAttribute = Attribute::where('value', $answer->chosenAttribute)
            ->first();

        if (!$answerAttribute) {
            return false;
        }

        $value = $answer->answerAttribute;

        if ($languageShort !== 'en' && $type === QuestionType::GAP) {
            $attributeRepository = new AttributeRepository();
            $value = $attributeRepository->getAttributeValueFromTranslation($value, $learnLanguage);
        }

        $relatedSentence = Attribute::where('value', strtolower($value))
            ->first();

        if (!$relatedSentence) {
            return false;
        }

        $answerSentenceId = $answerAttribute->relatedSentence()
            ->first()
            ->id;

        $relatedSentenceId = $relatedSentence->relatedSentence()
            ->first()
            ->id;

        return $answerSentenceId === $relatedSentenceId;
    }
}
