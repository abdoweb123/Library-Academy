<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\SectorRequest;
use App\Models\Sector;
use Illuminate\Http\Request;

class SectorController extends Controller
{

    public function store(SectorRequest $request)
    {
        Sector::create([
    
            'company_id' => getActiveCompany()->id,
            'name' => $request->name,
            'arrange' => Sector::max('arrange') + 1,
    
        ]);
    
        return response()->json([
            'success' => true
        ]);
    }


    public function update(SectorRequest $request, Sector $sector)
    {
        $sector->update([
            'name' => $request->name
        ]);

        return response()->json([
            'success' => true
        ]);
    }


    public function reorder(Request $request)
    {
        foreach ($request->ordered_ids as $index => $id) {

            Sector::where('id', $id)->update([
                'arrange' => $index + 1
            ]);
        }

        return response()->json([
            'success' => true
        ]);
    }

}
