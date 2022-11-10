<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAction extends Model
{
    protected $fillable = [
        'round',
        'name',
        'IP',
        'session_id',
        'mistakes',
        'user',
        'action',
        'value',
    ];
}
