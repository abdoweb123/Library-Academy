<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CabinetRequest;
use App\Models\Cabinet;

class CabinetController extends Controller
{

    public function store(CabinetRequest $request)
    {
        Cabinet::create([
            'name' => $request->name,
            'row_id' => $request->row_id,
            'arrange' => Cabinet::max('arrange') + 1,
    
        ]);
    
        return response()->json([
            'success' => true
        ]);
    }



}
