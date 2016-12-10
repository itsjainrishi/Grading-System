<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
     protected $fillable = [
        'id','stream', 'batch', 'semester',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
