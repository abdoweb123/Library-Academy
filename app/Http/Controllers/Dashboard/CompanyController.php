<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CompanyRequest;
use App\Models\Company;
use App\Repository\CompanyRepositoryInterface;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    protected $company;

    public function __construct(CompanyRepositoryInterface $company)
    {
        $this->company = $company;

        $this->middleware('can:show_companies')->only('index');
        $this->middleware('can:create_companies')->only('store');
        $this->middleware('can:edit_companies')->only('update');
        $this->middleware('can:delete_companies')->only('destroy');
    }

    public function index(Request $request)
    {
        return $this->company->index($request);
    }

    public function store(CompanyRequest $request)
    {
        return $this->company->store($request);
    }

    public function update(CompanyRequest $request, Company $company)
    {
        return $this->company->update($request, $company);
    }

    public function destroy(Company $company)
    {
        return $this->company->destroy($company);
    }

    public function setActive(Request $request, $id)
    {
        return $this->company->setActive($request, $id);
    }

}
