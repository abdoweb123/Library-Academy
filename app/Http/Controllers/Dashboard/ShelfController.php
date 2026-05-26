<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\ShelfRequest;
use App\Models\Shelf;


class ShelfController extends Controller
{

    public function store(ShelfRequest $request)
    {
        Shelf::create([
            'name' => $request->name,
            'cabinet_id' => $request->cabinet_id,
            'arrange' => Shelf::max('arrange') + 1,
    
        ]);
    
        return response()->json([
            'success' => true
        ]);
    }



}
