<?php

namespace App\Repository;

interface RoleRepositoryInterface extends RepositoryInterface
{
    public function index($request);

    public function store($request);

    public function update($request, $role);

    public function destroy($role);

} //end of class
