<?php

namespace App\Repositories;

use App\Models\Attribute;
use App\Models\Translation;

class AttributeRepository
{
    public function getTranslatedAttribute(string $key, string $language): string
    {
        $languageShort = substr($language, 0, 2);
        if ($languageShort === 'en') {
            return $key;
        }

        return Attribute::where('value', $key)
            ->first()
            ->relatedTranslation()
            ->byLanguage($language)
            ->first()
            ->value;
    }

    public function getAttributeValueFromTranslation(string $value, string $language): string
    {
        return Translation::where('value', strtolower($value))
            ->byLanguage($language)
            ->first()
            ->relatedAttribute()
            ->first()
            ->value;
    }
}
