<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToCompany
{
    protected static function bootBelongsToCompany()
    {
        static::creating(function ($model) {
            if (empty($model->company_id)) {
                $activeCompany = getActiveCompany();
                if ($activeCompany) {
                    $model->company_id = $activeCompany->id;
                }
            }
        });
    }
}
