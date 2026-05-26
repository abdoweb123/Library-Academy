<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
//    use BelongsToCompany, HasCompanyScope;

    protected $fillable = [
        'id',
        'name',
        'permissions',
        'company_id',
    ];

     // مشرف عام
    const GENERAL_SUPERVISOR = 'general_supervisor';

     // قائد أو مدير المكتبة
    const LIBRARY_MANAGER = 'library_manager';

     // أمين مكتبة
    const LIBRARIAN = 'librarian';

     // موظف إدخال بيانات
    const DATA_ENTRY = 'data_entry';

     // مسؤول الاستعارات
    const BORROWING_OFFICER = 'borrowing_officer';

     // مسؤول الجرد
    const INVENTORY_OFFICER = 'inventory_officer';

     // مسؤول الأرشفة
    const ARCHIVE_OFFICER = 'archive_officer';

     // مسؤول الطلاب / المشتركين
    const STUDENT_AFFAIRS = 'student_affairs';

     // مسؤول التقارير
    const REPORT_VIEWER = 'report_viewer';

    // get all constants
    public static function roles(): array
    {
        return (new \ReflectionClass(self::class))->getConstants();
    }

    // json_decode for (permissions)
    protected function permissions(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
        );
    }


} //end of class
