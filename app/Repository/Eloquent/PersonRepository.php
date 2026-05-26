<?php

namespace App\Repository\Eloquent;

use App\Http\Services\CrudService;
use App\Http\Services\Dashboard\PersonService;
use App\Http\Traits\FileManager;
use App\Models\Person;
use App\Models\User;
use App\Repository\PersonRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;

class PersonRepository extends Repository implements PersonRepositoryInterface
{
    use PersonService, FileManager, CrudService;

    protected Model $model;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function index($request)
    {
        $createRoute = 'dashboard.people.store';
        $editRoute = 'dashboard.people.update';
        $deleteRoute = 'dashboard.people.destroy';

        $models = Person::query()->get();

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
                                data-table="people"
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

        return view('dashboard.people.index', compact('models','inputs','variables','createRoute'));
    }

    public function store($request)
    {
        Person::create($request->all());
        return response()->json(['success' => 'Created successfully.']);
    }

    public function update($request, $person)
    {
        $person->update($request->all());
        return response()->json(['message' => 'Updated successfully'], 200);
    }

    public function destroy($person)
    {
        $person->delete();
    }

    public function setActive($request, $id)
    {
        $person = Person::find($id);
        if (!$person) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        // إذا كان غير مفعل، فعّله واجعل الباقي غير مفعل
        if ($person->active == 0) {
            $person->active = 1;
            $person->save();
            return response()->json(['success' => true, 'active' => 1]);
        }
        
        $person->active = 0;
        $person->save();

        // إذا كان مفعل بالفعل، لا تفعل شيء (التحكم في JS)
        return response()->json(['success' => false, 'active' => 1]);
    }

}
