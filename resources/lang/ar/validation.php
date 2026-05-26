<?php

return [

    'required' => 'حقل :attribute مطلوب.',
    'accepted' => 'يجب قبول :attribute.',
    'active_url' => ':attribute ليس عنوان URL صالحًا.',
    'after' => 'يجب أن يكون :attribute تاريخًا بعد :date.',
    'after_or_equal' => 'يجب أن يكون :attribute تاريخًا بعد أو يساوي :date.',
    'alpha' => 'يجب أن يحتوي :attribute على أحرف فقط.',
    'alpha_dash' => 'يجب أن يحتوي :attribute على أحرف وأرقام وشرطات.',
    'alpha_num' => 'يجب أن يحتوي :attribute على أحرف وأرقام فقط.',
    'array' => 'يجب أن يكون :attribute مصفوفة.',

    'between' => [
        'numeric' => 'يجب أن يكون :attribute بين :min و :max.',
        'file' => 'يجب أن يكون حجم :attribute بين :min و :max كيلوبايت.',
        'string' => 'يجب أن يكون :attribute بين :min و :max أحرف.',
        'array' => 'يجب أن يحتوي :attribute على بين :min و :max عناصر.',
    ],

    'boolean' => 'يجب أن يكون :attribute صحيح أو خطأ.',
    'confirmed' => 'تأكيد :attribute غير متطابق.',
    'date' => ':attribute ليس تاريخًا صالحًا.',

    'email' => 'يجب أن يكون :attribute بريد إلكتروني صالح.',
    'file' => 'يجب أن يكون :attribute ملف.',
    'image' => 'يجب أن يكون :attribute صورة.',
    'integer' => 'يجب أن يكون :attribute رقم صحيح.',
    'numeric' => 'يجب أن يكون :attribute رقم.',

    'required_if' => 'حقل :attribute مطلوب عندما يكون :other = :value.',
    'required_unless' => 'حقل :attribute مطلوب ما لم يكن :other ضمن :values.',

    'same' => 'يجب أن يتطابق :attribute مع :other.',

    'min' => [
        'numeric' => 'يجب أن يكون :attribute على الأقل :min.',
        'string' => 'يجب أن يكون :attribute على الأقل :min أحرف.',
        'array' => 'يجب أن يحتوي :attribute على الأقل :min عناصر.',
    ],

    'max' => [
        'numeric' => 'يجب ألا يكون :attribute أكبر من :max.',
        'string' => 'يجب ألا يكون :attribute أكثر من :max أحرف.',
        'array' => 'يجب ألا يحتوي :attribute أكثر من :max عناصر.',
    ],

    'unique' => 'قيمة :attribute مستخدمة بالفعل.',

    'url' => ':attribute ليس رابط صحيح.',

    /*
    |-----------------------------
    | Custom Attributes (IMPORTANT)
    |-----------------------------
    */

    'attributes' => [
        'title' => 'عنوان الكتاب',
        'publisher_id' => 'الناشر',
        'section_id' => 'القسم',
        'authors' => 'المؤلفين',
        'translators' => 'المترجمين',
        'copies' => 'النسخ',
        'copies.*.general_number' => 'رقم النسخة',
        'copies.*.book_code' => 'كود الكتاب',
        'student_id' => 'الطالب',
        'book_id' => 'الكتاب',
        'book_copy_id' => 'نسخة الكتاب',
        'borrow_date' => 'تاريخ الاستعارة',
        'due_date' => 'تاريخ الاستحقاق',
        'return_date' => 'تاريخ الإرجاع',
        'status' => 'الحالة',
        'name' => 'الاسم',
    ],
];