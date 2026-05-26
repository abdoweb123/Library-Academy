<?php

namespace App\Repository;

interface CourseRepositoryInterface
{
    public function index($request);
    public function store($request);
    public function update($request, $course);
    public function destroy($course);
    public function setActive($request, $id);
}
