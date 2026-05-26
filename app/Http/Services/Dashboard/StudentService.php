<?php

namespace App\Http\Services\Dashboard;

use App\Http\Services\MainService;
use App\Models\Course;

trait StudentService
{
    use MainService;

    public function modalInputs($model = null)
    {
        $courses = Course::select('id','name')->get();

        $courseOptions = [];
        foreach ($courses as $course) {
            $courseOptions[$course->id] = $course->name;
        }

        $selectedCourses = $model ? (is_array($model->course_id) ? $model->course_id : [$model->course_id]) : [];

        $inputs = [
            [
                'type' => 'hidden',
                'name' => 'id',
                'value' => $model ? $model->id : null,
                'show' => 0,
            ],
            [
                'type' => 'text',
                'name' => 'name',
                'label' => trns('name'),
                'cols' => 'col-md-6',
                'value' => $model?->name,
                'show' => 1,
            ],
            [
                'type' => 'email',
                'name' => 'email',
                'label' => trns('email'),
                'cols' => 'col-md-6',
                'value' => $model?->email,
                'show' => 1,
            ],
            [
                'type' => 'password',
                'name' => 'password',
                'label' => trns('password'),
                'cols' => 'col-md-6',
                'value' => null,
                'show' => 1,
            ],
            [
                'type' => 'text',
                'name' => 'phone',
                'label' => trns('phone'),
                'cols' => 'col-md-6',
                'value' => $model?->phone,
                'show' => 1,
            ],
            [
                'type' => 'select',
                'name' => 'course_id',
                'label' => trns('courses'),
                'options' => $courseOptions, // assuming $permissions is an associative array
                'selected_options' => $model ? $selectedCourses : [],
                'show' => 1,
            ],
        ];

        return $inputs;
    }

    public function modalVariables()
    {
        $variables = [
            'modal_dialog_width' => 'min-width: 40vw;',
            'cols' => 'col-md-12',
            'main_row' => 'justify-content-around px-4',
            'save_class' => 'mx-4',
        ];

        return $variables;
    }
}
