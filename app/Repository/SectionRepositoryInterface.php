<?php

namespace App\Repository;

interface SectionRepositoryInterface
{
    public function index($request);
    public function store($request);
    public function update($request, $section);
    public function destroy($section);
    public function setActive($request, $id);
}
