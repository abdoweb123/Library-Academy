<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'course_id',
    ];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function course()
    {
        return $this->belongsTo(course::class);
    }
}
