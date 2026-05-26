<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\RoleRequest;
use App\Models\Role;
use App\Repository\RoleRepositoryInterface;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $role;

    public function __construct(RoleRepositoryInterface $role)
    {
        $this->role = $role;
    }


    public function index(Request $request)
    {
        return $this->role->index($request);
    }


    public function store(RoleRequest $request)
    {
        return $this->role->store($request);
    }


    public function update(RoleRequest $request, Role $role)
    {
        return $this->role->update($request,$role);
    }


    public function destroy(Role $role)
    {
        return $this->role->destroy($role);
    }


} //end of class
