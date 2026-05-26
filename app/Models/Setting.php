<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Setting extends Authenticatable
{

    protected $fillable = [
        'key',
        'value',
        'type',
        'input_type',
        'company_id',
    ];

    protected $table = 'settings';

}
