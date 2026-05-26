<?php

namespace App\Http\Services\Dashboard;

use App\Http\Services\CrudService;
use App\Models\Role;
use Illuminate\Http\Request;

trait RoleService
{
    use CrudService;

    // Generic inputs method
    public function modalInputs($model = null)
    {
        $roleOptions = [];

        foreach (Role::roles() as $role) {
        
            $roleOptions[$role] = trns($role);
        
        }
        
        $permissions = permissions('permissions');

        $inputs = [
            [
                'type' => 'hidden',
                'name' => 'id',
                'value' => $model ? $model->id : null,
                'show' => 0,
            ],
            [
                'type' => 'select',
                'name' => 'name',     
                'label' => trns('roles'),        
                'options' => $roleOptions,         
                'selected_options' => $model ? [$model->role] : [],      
                'show' => 1,
            ],
            [
                'type' => 'checkbox',
                'name' => 'permissions',
                'label' => trns('permissions'),
                'options' => collect($permissions)->map(function($label, $value) {
                    return ['value' => $value, 'label' => ucfirst($label)];
                })->toArray(),

                'checked_values' => $model ? $model->permissions : [],
                'show' => 1,
            ],
        ];

        return $inputs;
    }

    // Generic variables method
    public function modalVariables()
    {
        $variables = [
            'modalClass' => 'custom-modal-class',
            'modalStyle' => 'background-color: #f0f0f0;',
            'modal_dialog_width' => 'min-width: 70vw;',
            'minCols' => 'col-md-6',
        ];

        return $variables;
    }

    // save role && permissions
    public function saveRolePermissions(Role $role, Request $request)
    {
        $role->name = $request->name;
        $role->permissions = json_encode($request->permissions);
        $role->save();

        return $role;
    }




} //end of class
