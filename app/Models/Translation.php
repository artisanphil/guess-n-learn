<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
}
