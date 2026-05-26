<?php

namespace App\Repository;

interface StudentRepositoryInterface
{
    public function index($request);
    public function store($request);
    public function update($request, $student);
    public function destroy($student);
}
