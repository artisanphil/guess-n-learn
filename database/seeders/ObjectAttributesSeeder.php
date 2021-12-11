<?php

namespace Database\Seeders;

use App\Models\Sentence;
use App\Models\Attribute;
use App\Models\ObjectAttribute;
use App\Models\ObjectModel;
use App\Models\Translation;
use Illuminate\Database\Seeder;
use App\Models\SentenceAttribute;
use App\Models\SentenceTranslation;
use PhpParser\Node\Expr\Cast\Object_;

class ObjectAttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $objectsJson = file_get_contents(base_path('resources/json/characters.json'));

        $objectsWithAttributes = json_decode($objectsJson, true);

        foreach ($objectsWithAttributes as $data) {
            $object = ObjectModel::create([
                'name' => $data['name']
            ]);

            $attributes = Attribute::whereIn('value', $data['attributes'])->get();

            $data = [];
            $i = 0;
            foreach ($attributes as $attribute) {
                $data[$i]['object_id'] = $object->id;
                $data[$i]['attribute_id'] = $attribute->id;
                $i++;
            }

            ObjectAttribute::insert($data);
        }
    }
}
