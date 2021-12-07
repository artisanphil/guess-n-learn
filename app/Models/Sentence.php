<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Sentence extends Model
{
    protected $fillable = [
        'value'
    ];
    public $timestamps = false;

    public function translations(): HasManyThrough
    {
        return $this->hasManyThrough(
            Translation::class,
            SentenceTranslation::class,
            'sentence_id',
            'id',
            'id',
            'translation_id'
        );
    }
}
