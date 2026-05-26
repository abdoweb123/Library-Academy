<?php

namespace App\Repository;

interface BorrowingRepositoryInterface
{
    public function index($request);
    public function create();
    public function store($request);
    public function edit($borrowing);
    public function update($request, $borrowing);
    public function show($borrowing);
    public function destroy($borrowing);
}
