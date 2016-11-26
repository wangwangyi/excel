<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    protected $fillable = [
        'in_num', 'repetition_in','repetition_out',
    ];
}
