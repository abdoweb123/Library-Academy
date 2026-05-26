<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\SettingRequest;
use App\Repository\SettingRepositoryInterface;

class SettingController extends Controller
{
    protected $setting;

    public function __construct(SettingRepositoryInterface $setting)
    {
        $this->setting = $setting;
    }

    public function edit($type = null)
    {
        return $this->setting->edit($type);
    }


    public function update(SettingRequest $request)
    {
        return $this->setting->update($request);
    }


    public function changeLanguage($lang)
    {
        return $this->setting->changeLanguage($lang);
    }



}
