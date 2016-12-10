<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
     protected $fillable = [
        'course_id', 'course_name', 'stream', 'batch', 'semester', 'faculty', 'weightage',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    
}
