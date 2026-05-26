<?php

namespace App\Repository;

interface BookRepositoryInterface
{
    public function index($request);
    public function create();
    public function store($request);
    public function edit($book);
    public function update($request, $book);
    public function show($book);
    public function destroy($book);
    public function setActive($request, $id);
}
