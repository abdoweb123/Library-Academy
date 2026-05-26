<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabinet extends Model
{
    protected $fillable = [
        'row_id',
        'name',
        'arrange',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function row()
    {
        return $this->belongsTo(Row::class);
    }

    public function shelves()
    {
        return $this->hasMany(Shelf::class);
    }
}
