<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\SectionRequest;
use App\Models\Section;
use App\Repository\SectionRepositoryInterface;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    protected $section;

    public function __construct(SectionRepositoryInterface $section)
    {
        $this->section = $section;

        $this->middleware('can:show_sections')->only('index');
        $this->middleware('can:create_sections')->only('store');
        $this->middleware('can:edit_sections')->only('update');
        $this->middleware('can:delete_sections')->only('destroy');
    }

    public function index(Request $request)
    {
        return $this->section->index($request);
    }

    public function store(SectionRequest $request)
    {
        return $this->section->store($request);
    }

    public function update(SectionRequest $request, Section $section)
    {
        return $this->section->update($request, $section);
    }

    public function destroy(Section $section)
    {
        return $this->section->destroy($section);
    }

    public function setActive(Request $request, $id)
    {
        return $this->section->setActive($request, $id);
    }

    public function search(Request $request)
    {
        return Section::where('name', 'like', "%{$request->q}%")
            ->select('id', 'name')
            ->limit(10)
            ->get();
    }
    
    public function storeAjax(Request $request)
    {
        $section = Section::create([
            'name' => $request->name
        ]);

        return response()->json($section);
    }

}
