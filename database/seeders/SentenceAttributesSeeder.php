<?php

namespace Database\Seeders;

use App\Models\Sentence;
use App\Models\Attribute;
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

        $languages = ['es'];

        foreach ($sentencesWithAttributes as $data) {
            $sentence = Sentence::create([
                'value' => $data['sentence']
            ]);

            foreach ($languages as $language) {
                $this->addSentenceTranslation($sentence, $data, $language);
            }

            $attribute = Attribute::create([
                'attribute' => $data['attribute']
            ]);

            SentenceAttribute::create([
                'attribute_id' => $attribute->id,
                'sentence_id' => $sentence->id
            ]);

            if (isset($data['alternatives'])) {
                foreach ($data['alternatives'] as $alternative) {
                    $alternativeAttribute = Attribute::create([
                        'attribute' => $alternative
                    ]);

                    SentenceAttribute::create([
                        'attribute_id' => $alternativeAttribute->id,
                        'sentence_id' => $sentence->id
                    ]);
                }
            }
        }
    }

    protected function addSentenceTranslation(Sentence $sentence, array $data, string $language)
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
}
