<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'name',
        'description',
        'active',
    ];
    
    
    public function books()
    {
        return $this->hasMany(Book::class);
    }

}
