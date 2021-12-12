<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Translation extends Model
{
    protected $fillable = [
        'value',
        'language'
    ];
    public $timestamps = false;

    public function scopeByLanguage(Builder $query, string $language): Builder
    {
        $languageShort = substr($language, 0, 2);

        return $query->where('language', $languageShort);
    }

    public function relatedAttribute(): HasOneThrough
    {
        return $this->hasOneThrough(
            Attribute::class,
            AttributeTranslation::class,
            'translation_id',
            'id',
            'id',
            'attribute_id'
        );
    }
}
