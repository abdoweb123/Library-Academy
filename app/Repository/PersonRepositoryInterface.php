<?php

namespace App\Repository;

interface PersonRepositoryInterface
{
    public function index($request);
    public function store($request);
    public function update($request, $Person);
    public function destroy($Person);
    public function setActive($request, $id);
}
