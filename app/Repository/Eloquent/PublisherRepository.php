<?php

namespace App\Repository\Eloquent;

use App\Http\Services\CrudService;
use App\Http\Services\Dashboard\PublisherService;
use App\Http\Traits\FileManager;
use App\Models\Publisher;
use App\Models\User;
use App\Repository\PublisherRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;

class PublisherRepository extends Repository implements PublisherRepositoryInterface
{
    use PublisherService, FileManager, CrudService;

    protected Model $model;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function index($request)
    {
        $createRoute = 'dashboard.publishers.store';
        $editRoute = 'dashboard.publishers.update';
        $deleteRoute = 'dashboard.publishers.destroy';

        $models = Publisher::query()->get();

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
                                data-table="publishers"
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

        return view('dashboard.publishers.index', compact('models','inputs','variables','createRoute'));
    }

    public function store($request)
    {
        Publisher::create($request->all());
        return response()->json(['success' => 'Created successfully.']);
    }

    public function update($request, $publisher)
    {
        $publisher->update($request->all());
        return response()->json(['message' => 'Updated successfully'], 200);
    }

    public function destroy($publisher)
    {
        $publisher->delete();
    }

    public function setActive($request, $id)
    {
        $publisher = Publisher::find($id);
        if (!$publisher) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        // إذا كان غير مفعل، فعّله واجعل الباقي غير مفعل
        if ($publisher->active == 0) {
            $publisher->active = 1;
            $publisher->save();
            return response()->json(['success' => true, 'active' => 1]);
        }
        
        $publisher->active = 0;
        $publisher->save();

        // إذا كان مفعل بالفعل، لا تفعل شيء (التحكم في JS)
        return response()->json(['success' => false, 'active' => 1]);
    }

}
