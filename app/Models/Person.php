<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $fillable = [
        'name',
        'bio',
        'active',
    ];

   
    public function books()
    {
        return $this->belongsToMany(Book::class)
            ->withPivot('role')
            ->withTimestamps();
    }
    
    //Show Full name (optional)
    public function getFullNameAttribute()
    {
        return $this->title
            ? $this->title . ' ' . $this->name
            : $this->name;
    }

}
