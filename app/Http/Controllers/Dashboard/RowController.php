<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\RowRequest;
use App\Models\Row;

class RowController extends Controller
{

    public function store(RowRequest $request)
    {
        Row::create([
            'name' => $request->name,
            'side' => $request->side,
            'sector_id' => $request->sector_id,
            'arrange' => Row::max('arrange') + 1,
    
        ]);
    
        return response()->json([
            'success' => true
        ]);
    }



}
