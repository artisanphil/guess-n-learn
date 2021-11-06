<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentenceAttribute extends Model
{
    protected $fillable = [
        'attribute_id',
        'sentence_id'
    ];
    public $timestamps = false;

    public function attribute()
    {
        return $this->belongsTo('App\Models\Attribute');
    }
}
