<?php

namespace App\Repository;

interface AdminRepositoryInterface extends RepositoryInterface
{
    public function index($request);

    public function store($request);

    public function update($request, $admin);

    public function destroy($admin);

} //end of class
