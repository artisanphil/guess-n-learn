<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentenceTranslation extends Model
{
    protected $fillable = [
        'sentence_id',
        'translation_id'
    ];
    public $timestamps = false;
}
