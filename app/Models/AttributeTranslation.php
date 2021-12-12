<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class AttributeTranslation extends Model
{
    protected $fillable = [
        'attribute_id',
        'translation_id'
    ];
    public $timestamps = false;
}
