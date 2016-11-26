<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Excel extends Model
{
    protected $fillable = [
        'name', 'email', 'tel','create_time',
    ];
}
