<?php

namespace App\Repository;

interface CompanyRepositoryInterface
{
    public function index($request);
    public function store($request);
    public function update($request, $company);
    public function destroy($company);
    public function setActive($request, $id);
}
