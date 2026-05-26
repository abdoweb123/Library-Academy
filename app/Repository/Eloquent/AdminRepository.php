<?php

namespace App\Repository\Eloquent;


use App\Http\Services\CrudService;
use App\Http\Services\Dashboard\AdminService;
use App\Http\Traits\FileManager;
use App\Models\Admin;
use App\Models\User;
use App\Repository\AdminRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;

class AdminRepository extends Repository implements AdminRepositoryInterface
{
    use AdminService, FileManager, CrudService;

    protected Model $model;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function index($request)
    {

        $createRoute = 'dashboard.admin.admins.store'; // Replace with your edit route name
        $editRoute = 'dashboard.admin.admins.update'; // Replace with your edit route name
        $deleteRoute = 'dashboard.admin.admins.destroy'; // Replace with your delete route name

        $models = Admin::latest()->get();
        if ($request->ajax()) {

            return DataTables::of($models)
                ->addIndexColumn()
                ->addColumn('action', function ($model) use ($editRoute, $deleteRoute) {
                    return $this->renderActionModals($model, $editRoute, $deleteRoute);
                })
                ->addColumn('image', function($row) {
                    $imageSrc = getModelImage($row->image); // Replace 'dasd' with your default image path
                    return '<img src="' . $imageSrc . '" alt="Image" width="50" height="50">';
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }

        // inputs && variables in create modal
        $inputs = $this->modalInputs();
        $variables = $this->modalVariables();
        return view('dashboard.admins.index', compact('models','inputs','variables','createRoute'));
    }


    public function store($request)
    {
        $data = $request->except('image');

        // if there is an image store it
        if ($request->hasFile('image')) {
            // Pass the field name 'image' to the upload
            $imagePath = $this->upload('image', 'admins');
            $data['image'] = $imagePath;
        }

        // Manually hash the password before storing it
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        Admin::create($data);

        return response()->json(['success' => 'Group created successfully.']);
    }


    public function update($request, $admin)
    {
        $data = $request->except('image');

        // if there is an image, store it
        if ($request->hasFile('image')) {
            // Pass the field name 'image' to the upload
            $imagePath = $this->upload('image', 'admins');
            $data['image'] = $imagePath;

            // Delete the old image
            $this->deleteFile($admin->image);
        }

        // Manually hash the password before storing it
        if (isset($data['password']) && $data['password']) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $admin->update($data);

        return response()->json(['success' => 'Admin updated successfully.']);
    }


    public function destroy($admin)
    {
        if ($admin){
            // Delete the old image
            $this->deleteFile($admin->image);

            $admin->delete();
        }

        return response()->json(['message' => 'Deleted successfully'], 200);
    }



} //end of class
