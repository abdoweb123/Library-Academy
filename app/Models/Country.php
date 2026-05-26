<?php

namespace App\Models;

use App\Http\Services\MainService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends BaseModel
{
    use HasFactory, MainService;

    protected $fillable = [
        'title_ar',
        'title_en',
        'currancy_code_ar',
        'currancy_code_en',
        'currancy_value',
        'phone_code',
        'country_code',
        'length',
        'decimals',
        'lat',
        'long',
        'status',
        'image',
        'company_id',
    ];

    public function getTitleAttribute()
    {
        return $this->title_en;
    }

} //end of class
