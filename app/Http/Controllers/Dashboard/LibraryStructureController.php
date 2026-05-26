<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
// use App\Http\Requests\Dashboard\LibraryStructureRequest;
use App\Models\Sector;
use App\Models\Shelf;
use Illuminate\Http\Request;

class LibraryStructureController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:side_library_structure')->only('index');  
    }

    public function index(Request $request)
    {
        $sectors = Sector::where('company_id', getActiveCompany()->id)
            ->with([
                'rows.cabinets.shelves.bookCopies' => function ($q) {
                    $q->orderBy('shelf_order');
                },
                'rows.cabinets.shelves.bookCopies.book'
            ])
            ->orderBy('arrange')
            ->get();

        return view('dashboard.library_structure.index', compact(
            'sectors'
        ));
    }


    public function searchLocations(Request $request)
    {
        return Shelf::with([
                'cabinet.row.sector'
            ])
            ->where('name', 'like', "%{$request->q}%")
            ->limit(10)
            ->get()
            ->map(function ($shelf) {
    
                return [
                    'id' => $shelf->id,
    
                    'name' =>
                        ($shelf->cabinet->row->sector->name ?? '') . ' - ' .
                        ($shelf->cabinet->row->name ?? '') . ' - ' .
                        ($shelf->cabinet->name ?? '') . ' - ' .
                        $shelf->name
                ];
            });
    }

   

}
