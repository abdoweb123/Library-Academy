<?php

namespace App\Repository\Eloquent;

use App\Http\Services\CrudService;
use App\Http\Services\Dashboard\CourseService;
use App\Http\Traits\FileManager;
use App\Models\Course;
use App\Models\User;
use App\Repository\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;

class CourseRepository extends Repository implements CourseRepositoryInterface
{
    use CourseService, FileManager, CrudService;

    protected Model $model;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function index($request)
    {
        $createRoute = 'dashboard.courses.store';
        $editRoute = 'dashboard.courses.update';
        $deleteRoute = 'dashboard.courses.destroy';

        $models = Course::query()->get();

        if ($request->ajax()) {
            return DataTables::of($models)
                ->addIndexColumn()
                ->addColumn('action', function ($model) use ($editRoute, $deleteRoute) {
                    return $this->renderActionModals($model, $editRoute, $deleteRoute);
                })
                ->addColumn('active', function ($model) {
                    $checked = $model->active ? 'checked' : '';
                    return '
                    <input type="checkbox" class="toggle-active"
                                data-table="courses"
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

        return view('dashboard.courses.index', compact('models','inputs','variables','createRoute'));
    }

    public function store($request)
    {
        Course::create($request->all());
        return response()->json(['success' => 'Created successfully.']);
    }

    public function update($request, $course)
    {
        $course->update($request->all());
        return response()->json(['message' => 'Updated successfully'], 200);
    }

    public function destroy($course)
    {
        $course->delete();
    }

    public function setActive($request, $id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        // إذا كان غير مفعل، فعّله واجعل الباقي غير مفعل
        if ($course->active == 0) {
            $course->active = 1;
            $course->save();
            return response()->json(['success' => true, 'active' => 1]);
        }
        
        $course->active = 0;
        $course->save();

        // إذا كان مفعل بالفعل، لا تفعل شيء (التحكم في JS)
        return response()->json(['success' => false, 'active' => 1]);
    }

}
