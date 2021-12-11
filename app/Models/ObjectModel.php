<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ObjectModel extends Model
{
    protected $table = 'objects';
    protected $fillable = [
        'name'
    ];
    public $timestamps = false;

    public function attributes(): HasManyThrough
    {
        return $this->hasManyThrough(
            Attribute::class,
            ObjectAttribute::class,
            'object_id',
            'id',
            'id',
            'attribute_id'
        );
    }
}
