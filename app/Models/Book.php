<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Book extends Model
{
    protected $fillable = [
        'title',
        'section_id',
        'company_id',
        'active',
        
    ];

    // 🔥 Book Copies
    public function copies()
    {
        return $this->hasMany(BookCopy::class);
    }

    // 📚 Section
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function people()
    {
        return $this->belongsToMany(Person::class)
            ->using(BookPerson::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    

    // Helpers
    public function authors()
    {
        return $this->people()->wherePivot('role', 'author');
    }

    public function translators()
    {
        return $this->people()->wherePivot('role', 'translator');
    }

    public function getAuthorsNamesAttribute()
    {
        return $this->authors->pluck('name')->join(' - ');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function borrowings()
    {
        return $this->hasManyThrough(
            Borrowing::class,
            BookCopy::class,
            'book_id',      // foreign key في book_copies
            'book_copy_id', // foreign key في borrowings
            'id',           // local key في books
            'id'            // local key في book_copies
        );
    }

    // protected static function booted()
    // {
    //     static::addGlobalScope('active_company', function (Builder $builder) {
    //         $builder->whereHas('company', function ($q) {
    //             $q->where('active', 1);
    //         });
    //     });
    // }

    protected static function booted()
{
    static::addGlobalScope('active_company', function (Builder $builder) {

        if (request()->get('ignore_company_scope')) {
            return;
        }

        $builder->whereHas('company', function ($q) {
            $q->where('active', 1);
        });

    });
}

}
