<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookCopy extends Model
{
    protected $fillable = [
        'book_id',
        'shelf_id',
        'publisher_id',
        'general_number',
        'classification_number',
        'shelf_order',
        'book_code',
        'pages',
        'size',
        'publish_year',
        'status',
        'purchase_date',
        'price',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    
    // 🏢 Publisher
    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function shelf()
    {
        return $this->belongsTo(Shelf::class);
    }
}
