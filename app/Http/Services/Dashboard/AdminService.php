<?php

namespace App\Http\Services\Dashboard;

use App\Models\Role;

trait AdminService
{
    // Generic inputs method
    public function modalInputs($model = null)
    {
        $roles = Role::query()->select('id','name')->get();
        $companies = getAllCompanies();


        $roleOptions = [];
        foreach ($roles as $role) {
            $roleOptions[$role->id] = trns($role->name);
        }

        $companyOptions = [];
        foreach ($companies as $company) {
            $companyOptions[$company->id] = $company->name;
        }

        $selectedRoles = $model ? (is_array($model->role_id) ? $model->role_id : [$model->role_id]) : [];
        $selectedCompanies = $model ? (is_array($model->company_id) ? $model->company_id : [$model->company_id]) : [];

        $inputs = [
            [
                'type' => 'text',
                'name' => 'name',
                'label' => trns('Name'),
                'value' => $model ? $model->name : null,
                'show' => 1, //show to send only which inputs to be shown
            ],
            [
                'type' => 'email',
                'name' => 'email',
                'label' => trns('Email'),
                'value' => $model ? $model->email : null,
                'show' => 1,
            ],
            [
                'type' => 'text',
                'name' => 'phone',
                'label' => trns('phone'),
                'value' => $model ? $model->phone : null,
                'show' => 1,
            ],
            [
                'type' => 'password',
                'name' => 'password',
                'label' => trns('password'),
                'value' => '',
                'show' => 0,
            ],
            [
                'type' => 'file',
                'name' => 'image',
                'label' => trns('the_image'),
                'value' => $model ? $model->image : null,
                'show' => 0,
            ],
            [
                'type' => 'select',
                'name' => 'role_id',
                'label' => trns('roles'),
                'options' => $roleOptions, // assuming $permissions is an associative array
                'selected_options' => $model ? $selectedRoles : [],
                'show' => 1,
            ],
            [
                'type' => 'select',
                'name' => 'company_id',
                'label' => trns('company'),
                'options' => $companyOptions, // assuming $permissions is an associative array
                'selected_options' => $model ? $selectedCompanies : [],
                'show' => 1,
            ],
        ];

        return $inputs;
    }




    // Generic variables method
    public function modalVariables()
    {
        $variables = [
            'modal_dialog_width' => 'min-width: 60vw;',
            'cols' => 'col-md-6',
            'main_row' => 'justify-content-between px-4',
        ];

        return $variables;
    }


} //end of class
