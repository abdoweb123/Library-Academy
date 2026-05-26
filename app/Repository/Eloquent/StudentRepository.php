<?php

namespace App\Repository\Eloquent;

use App\Http\Services\CrudService;
use App\Http\Services\Dashboard\StudentService;
use App\Http\Traits\FileManager;
use App\Models\Student;
use App\Models\User;
use App\Repository\StudentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class StudentRepository extends Repository implements StudentRepositoryInterface
{
    use StudentService, FileManager, CrudService;

    protected Model $model;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function index($request)
    {
        $createRoute = 'dashboard.students.store';
        $editRoute = 'dashboard.students.update';
        $deleteRoute = 'dashboard.students.destroy';

        $models = Student::query()->latest()->get();

        if ($request->ajax()) {
            return DataTables::of($models)
                ->addIndexColumn()
                ->addColumn('action', function ($model) use ($editRoute, $deleteRoute) {
                    return $this->renderActionModals($model, $editRoute, $deleteRoute);
                })
                ->addColumn('created_at', function ($model) {
                    return optional($model->created_at)->format('Y-m-d H:i');
                })
                ->rawColumns(['name', 'email', 'phone', 'created_at', 'action'])
                ->make(true);
        }

        $inputs = $this->modalInputs();
        $variables = $this->modalVariables();

        return view('dashboard.students.index', compact('models', 'inputs', 'variables', 'createRoute'));
    }

    public function store($request)
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        Student::create($data);

        return response()->json(['success' => 'Created successfully.']);
    }

    public function update($request, $student)
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $student->update($data);

        return response()->json(['message' => 'Updated successfully'], 200);
    }

    public function destroy($student)
    {
        $student->delete();
    }
}
