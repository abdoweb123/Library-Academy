<?php

namespace App\Http\Services\Dashboard;

use App\Http\Services\MainService;

trait BookService
{
    use MainService;

    public function modalInputs($model = null)
    {

        $inputs = [
           
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
