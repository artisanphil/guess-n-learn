<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Attribute extends Model
{
    protected $fillable = [
        'attribute'
    ];
    public $timestamps = false;

    public function relatedSentence(): HasOneThrough
    {
        return $this->hasOneThrough(
            Sentence::class,
            SentenceAttribute::class,
            'attribute_id',
            'id',
            'id',
            'sentence_id'
        );
    }

    public function relatedTranslation(): HasOneThrough
    {
        return $this->hasOneThrough(
            Translation::class,
            AttributeTranslation::class,
            'attribute_id',
            'id',
            'id',
            'translation_id'
        );
    }
}
