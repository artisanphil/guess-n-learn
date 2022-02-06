<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAnswer extends Model
{
    protected $fillable = [
        'round',
        'type',
        'attribute',
        'input',
        'correct',
    ];
}
