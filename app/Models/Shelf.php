<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    protected $fillable = [
        'cabinet_id',
        'name',
        'arrange',
    ];

    
    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class);
    }

    public function bookCopies()
    {
        return $this->hasMany(BookCopy::class);
    }
}
