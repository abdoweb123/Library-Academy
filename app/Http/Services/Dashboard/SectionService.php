<?php

namespace App\Http\Services\Dashboard;

use App\Http\Services\MainService;

trait SectionService
{
    use MainService;

    public function modalInputs($model = null)
    {

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
                'type' => 'textarea',
                'name' => 'description',
                'label' => trns('description'),
                'cols' => 'col-md-12',
                'value' => $model?->name,
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
