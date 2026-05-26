<?php

namespace App\Repository;

interface PublisherRepositoryInterface
{
    public function index($request);
    public function store($request);
    public function update($request, $Publisher);
    public function destroy($Publisher);
    public function setActive($request, $id);
}
