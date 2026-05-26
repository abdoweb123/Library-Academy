<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BookPerson extends Pivot
{
    protected $table = 'book_person';

    protected $fillable = [
        'book_id',
        'person_id',
        'role',
    ];

    // علاقات (اختياري)
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
