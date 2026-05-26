<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'student_id',
        'book_copy_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
    ];

    //* Scopes
    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }

    public function scopeBorrowed($query)
    {
        return $query->where('status', 'borrowed');
    }
    
    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }



    //* Relations
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function bookCopy()
    {
        return $this->belongsTo(BookCopy::class);
    }

    
} // end of class
