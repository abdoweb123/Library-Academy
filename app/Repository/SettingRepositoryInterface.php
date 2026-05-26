<?php

namespace App\Repository;

interface SettingRepositoryInterface extends RepositoryInterface
{
    public function edit($type = null);

    public function update($request);

    public function changeLanguage($lang);


} //end of class
