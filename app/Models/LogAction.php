<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAction extends Model
{
    protected $fillable = [
        'IP',
        'user',
        'action',
        'value',
    ];
}
