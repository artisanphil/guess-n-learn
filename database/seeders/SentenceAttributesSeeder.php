<?php

namespace Database\Seeders;

use App\Models\Sentence;
use App\Models\Attribute;
use App\Models\AttributeAlternative;
use App\Models\AttributeTranslation;
use App\Models\Translation;
use Illuminate\Database\Seeder;
use App\Models\SentenceAttribute;
use App\Models\SentenceTranslation;

class SentenceAttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sentencesJson = file_get_contents(base_path('resources/json/character-sentences.json'));

        $sentencesWithAttributes = json_decode($sentencesJson, true);

        $languages = ['en', 'es', 'kr'];

        foreach ($sentencesWithAttributes as $data) {
            $sentence = Sentence::create([
                'value' => $data['sentence']
            ]);

            foreach ($languages as $language) {
                $this->addSentenceTranslation($sentence, $data, $language);
            }

            $attribute = Attribute::create([
                'value' => $data['attribute']
            ]);

            foreach ($languages as $language) {
                $this->addAttributeTranslation($attribute, $data, $language);
            }

            SentenceAttribute::create([
                'attribute_id' => $attribute->id,
                'sentence_id' => $sentence->id
            ]);

            foreach ($languages as $language) {
                $this->addAlternativeAttribute($attribute, $data, $language);
            }
        }
    }

    protected function addSentenceTranslation(Sentence $sentence, array $data, string $language): void
    {
        if (!isset($data['sentence_' . $language])) {
            return;
        }

        $value = $data['sentence_' . $language];
        $translation = Translation::create([
            'language' => $language,
            'value' => $value
        ]);

        SentenceTranslation::create([
            'sentence_id' => $sentence->id,
            'translation_id' => $translation->id
        ]);
    }

    protected function addAttributeTranslation(Attribute $attribute, array $data, string $language): void
    {
        if (!isset($data['attribute_' . $language])) {
            return;
        }

        $value = $data['attribute_' . $language];
        $translation = Translation::create([
            'language' => $language,
            'value' => $value
        ]);

        AttributeTranslation::create([
            'attribute_id' => $attribute->id,
            'translation_id' => $translation->id
        ]);
    }

    protected function addAlternativeAttribute(Attribute $attribute, array $data, string $language): void
    {
        if (!isset($data['alternatives_' . $language])) {
            return;
        }

        $alternatives = $data['alternatives_' . $language];

        foreach ($alternatives as $alternative) {
            AttributeAlternative::create([
                'attribute_id' => $attribute->id,
                'value' => $alternative,
                'language' => $language,
            ]);
        }
    }
}
