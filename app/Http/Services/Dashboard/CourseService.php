<?php

namespace App\Http\Services\Dashboard;

use App\Http\Services\MainService;

trait CourseService
{
    use MainService;

    public function modalInputs($model = null)
    {
        $selected_options = $model ? (is_array($model->active) ? $model->active : [$model->active]) : [];

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
                'cols' => 'col-md-12',
                'value' => $model?->name, 
                'show' => 1,
            ],
            [
                'type' => 'textarea',
                'name' => 'description',
                'label' => trns('description'),
                'cols' => 'col-md-12',
                'value' => $model?->name,
                'show' => 1,
            ],
            [
                'type' => 'text',
                'name' => 'supervisor_name',
                'label' => trns('supervisor_name'),
                'cols' => 'col-md-6',
                'value' => $model?->supervisor_name,
                'show' => 1,
            ],
            
            [
                'type' => 'text',
                'name' => 'supervisor_phone',
                'label' => trns('supervisor_phone'),
                'cols' => 'col-md-6',
                'value' => $model?->supervisor_phone,
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
