<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HttpLoggerRequest as ObjRequest;
use App\Models\HttpLogger as ObjModel;
use App\Services\Admin\HttpLoggerService as ObjService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HttpLoggerController extends Controller
{
    public function __construct(protected ObjService $objService) {}

    public function index(Request $request)
    {
        return $this->objService->index($request);
    }

    public function show(ObjRequest $request, ObjModel $objModel)
    {
        return $this->objService->show($request, $objModel);
    }

    public function destroy()
    {
        return $this->objService->deleteLog();
    }

}
