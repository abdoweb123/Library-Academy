<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Row extends Model
{
    protected $fillable = [
        'sector_id',
        'name',
        'side',
        'arrange',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function cabinets()
    {
        return $this->hasMany(Cabinet::class);
    }
}
