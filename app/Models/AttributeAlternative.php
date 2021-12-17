<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class AttributeAlternative extends Model
{
    protected $fillable = [
        'attribute_id',
        'value',
        'language',
    ];
    public $timestamps = false;
}
