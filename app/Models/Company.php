<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'active',
    ];

    

    public function sectors()
    {
        return $this->hasMany(Sector::class);
    }
} 