<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\AdminRequest;
use App\Models\Admin;
use App\Repository\AdminRepositoryInterface;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $admin;

    public function __construct(AdminRepositoryInterface $admin)
    {
        $this->admin = $admin;
    }


    public function index(Request $request)
    {
        return $this->admin->index($request);
    }


    public function store(AdminRequest $request)
    {
        return $this->admin->store($request);
    }


    public function update(AdminRequest $request, Admin $admin)
    {
        return $this->admin->update($request,$admin);
    }


    public function destroy(Admin $admin)
    {
        return $this->admin->destroy($admin);
    }


} //end of class
