<?php

namespace App\Repository\Eloquent;

use App\Http\Services\CrudService;
use App\Http\Services\Dashboard\CompanyService;
use App\Http\Traits\FileManager;
use App\Models\Company;
use App\Models\User;
use App\Repository\CompanyRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CompanyRepository extends Repository implements CompanyRepositoryInterface
{
    use CompanyService, FileManager, CrudService;

    protected Model $model;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function index($request)
    {
        $createRoute = 'dashboard.companies.store';
        $editRoute = 'dashboard.companies.update';
        $deleteRoute = 'dashboard.companies.destroy';

        $models = Company::query()->get();

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
                                data-table="companies"
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

        return view('dashboard.companies.index', compact('models','inputs','variables','createRoute'));
    }

    public function store($request)
    {
        Company::create($request->all());
        return response()->json(['success' => 'Created successfully.']);
    }

    public function update($request, $company)
    {
        $company->update($request->all());
        return response()->json(['message' => 'Updated successfully'], 200);
    }

    public function destroy($company)
    {
        $company->delete();
    }

    public function setActive($request, $id)
    {
        $company = Company::find($id);
        if (!$company) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        // إذا كان غير مفعل، فعّله واجعل الباقي غير مفعل
        if ($company->active == 0) {
            Company::where('active', 1)->update(['active' => 0]);
            $company->active = 1;
            $company->save();
            return response()->json(['success' => true, 'active' => 1]);
        }

        // إذا كان مفعل بالفعل، لا تفعل شيء (التحكم في JS)
        return response()->json(['success' => false, 'active' => 1]);
    }

}
