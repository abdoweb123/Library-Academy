<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'supervisor_name',
        'supervisor_phone',
        'active',
    ];

} 