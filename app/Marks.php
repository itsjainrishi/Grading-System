<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marks extends Model
{
    protected $fillable = [
        'clg_id', 'course_id', 'name', 'marks',
    ];
}
