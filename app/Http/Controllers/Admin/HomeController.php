<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\HomeService as ObjService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct(protected ObjService $objService) {}

    public function index()
    {
        return $this->objService->index();
    }

    public function updateFilterDate(Request $request)
    {
        return $this->objService->updateFilterDate($request);
    }



}

