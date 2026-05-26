<?php

namespace App\Services\Admin;

use App\Models\Admin;
use App\Models\Setting as ObjModel;
use App\Models\Setting as settingObj;
use App\Services\BaseService;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\DataTables\DataTables;

class SettingService extends BaseService
{
    protected string $folder = 'admin/setting';
    protected string $route = 'settings';
    protected SettingObj $settingObj;

    public function __construct(ObjModel $objModel, SettingObj $settingObj)
    {
        $this->settingObj = $settingObj;
        parent::__construct($objModel);
    }

}
