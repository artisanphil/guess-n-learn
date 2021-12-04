<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAnswer extends Model
{
    protected $fillable = [
        'type',
        'attribute',
        'input',
        'correct',
    ];
}
