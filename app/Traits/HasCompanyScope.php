<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait HasCompanyScope
{
    protected static function bootHasCompanyScope()
    {
        static::addGlobalScope('company', function (Builder $builder) {
            $admin = Auth::guard('admin')->user();

            if ($admin->role_id == 1) { // Super admin
                // Super admin: فلترة حسب الشركة المفعلة أو العامة
                $activeCompany = getActiveCompany();
                if ($activeCompany) {
                    $builder->where(function ($q) use ($activeCompany) {
                        $q->where('company_id', $activeCompany->id)
                            ->orWhereNull('company_id');
                    });
                }
            } else {
                // Admin عادي: فلترة حسب الشركة الخاصة به فقط
                $builder->where('company_id', $admin->company_id)
                   ->orWhereNull('company_id');
            }

        });
    }
}
