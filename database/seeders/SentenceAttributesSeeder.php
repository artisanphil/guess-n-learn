<?php

namespace Database\Seeders;

use App\Models\Sentence;
use App\Models\Attribute;
use App\Models\SentenceAttribute;
use Illuminate\Database\Seeder;

class SentenceAttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sentencesJson = file_get_contents(base_path('resources/json/character-sentences_en.json'));

        $sentencesWithAttributes = json_decode($sentencesJson, true);

        foreach ($sentencesWithAttributes as $data) {
            $sentence = Sentence::create([
                'sentence' => $data['sentence']
            ]);

            $attribute = Attribute::create([
                'attribute' => $data['attribute']
            ]);

            SentenceAttribute::create([
                'attribute_id' => $attribute->id,
                'sentence_id' => $sentence->id
            ]);
        }
    }
}
