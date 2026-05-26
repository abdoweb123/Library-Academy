<?php

namespace App\Repository\Eloquent;

use App\Http\Services\CrudService;
use App\Http\Services\Dashboard\SectionService;
use App\Http\Traits\FileManager;
use App\Models\Section;
use App\Models\User;
use App\Repository\SectionRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;

class SectionRepository extends Repository implements SectionRepositoryInterface
{
    use SectionService, FileManager, CrudService;

    protected Model $model;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function index($request)
    {
        $createRoute = 'dashboard.sections.store';
        $editRoute = 'dashboard.sections.update';
        $deleteRoute = 'dashboard.sections.destroy';

        $models = Section::query()->get();

        if ($request->ajax()) {
            return DataTables::of($models)
                ->addIndexColumn()
                ->addColumn('action', function ($model) use ($editRoute, $deleteRoute) {
                    return $this->renderActionModals($model, $editRoute, $deleteRoute);
                })
                ->addColumn('name', function ($model) {
                    return $model->title.' '.$model->name;
                })
                ->addColumn('active', function ($model) {
                    $checked = $model->active ? 'checked' : '';
                    return '
                    <input type="checkbox" class="toggle-active"
                                data-table="sections"
                                data-id="'.$model->id.'"
                                '.$checked.'
                                data-toggle="toggle"
                                data-on="'.trns('active').'"
                                data-off="'.trns('inactive').'"
                                data-onstyle="success"
                                data-offstyle="danger">
                    ';
                })
                ->rawColumns(['name','active','action'])
                ->make(true);
        }

        $inputs = $this->modalInputs();
        $variables = $this->modalVariables();

        return view('dashboard.sections.index', compact('models','inputs','variables','createRoute'));
    }

    public function store($request)
    {
        Section::create($request->all());
        return response()->json(['success' => 'Created successfully.']);
    }

    public function update($request, $section)
    {
        $section->update($request->all());
        return response()->json(['message' => 'Updated successfully'], 200);
    }

    public function destroy($section)
    {
        $section->delete();
    }

    public function setActive($request, $id)
    {
        $section = Section::find($id);
        if (!$section) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        // إذا كان غير مفعل، فعّله واجعل الباقي غير مفعل
        if ($section->active == 0) {
            $section->active = 1;
            $section->save();
            return response()->json(['success' => true, 'active' => 1]);
        }
        
        $section->active = 0;
        $section->save();

        // إذا كان مفعل بالفعل، لا تفعل شيء (التحكم في JS)
        return response()->json(['success' => false, 'active' => 1]);
    }

}
