<?php

namespace App\Repository\Eloquent;

use App\Http\Services\Dashboard\RoleService;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use App\Repository\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;

class RoleRepository extends Repository implements RoleRepositoryInterface
{
    use RoleService;
    protected Model $model;


    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function index($request)
    {
        $createRoute = 'dashboard.admin.roles.store';
        $editRoute = 'dashboard.admin.roles.update';
        $deleteRoute = 'dashboard.admin.roles.destroy';

        $models = Role::latest()->get();

        // 👇 دي للعرض العادي (non-ajax)
        $inputs = $this->modalInputs();
        $variables = $this->modalVariables();

        if ($request->ajax()) {
            return DataTables::of($models)
                ->addIndexColumn()
                ->addColumn('permissions', function ($model) {
                    $permissions = $model->permissions ?? [];

                    $limitedPermissions = array_slice($permissions, 0, 5);
                
                    $translated = array_map(function ($permission) {
                        return trns($permission);
                    }, $limitedPermissions);
                
                    $output = implode(', ', $translated);
                
                    if (count($permissions) > 5) {
                        $output .= ' ...';
                    }
                    return $output;
                })
                ->addColumn('name', function ($model) {
                    return trns($model->name);
                })
                ->addColumn('action', function ($model) use ($editRoute, $deleteRoute) {
                    return $this->renderActionModals($model, $editRoute, $deleteRoute);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashboard.roles.index', compact('models', 'inputs', 'variables', 'createRoute','editRoute','deleteRoute'));
    }



    public function store($request)
    {
        $this->saveRolePermissions(new Role, $request);
    }


    public function update($request, $role)
    {
        $this->saveRolePermissions($role, $request);
    }


    public function destroy($role)
    {
        $role->delete();

        return response()->json(['message' => 'Deleted successfully'], 200);
    }



} //end of class
