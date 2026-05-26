<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StudentRequest;
use App\Models\Student;
use App\Repository\StudentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    protected $student;

    public function __construct(StudentRepositoryInterface $student)
    {
        $this->student = $student;

        $this->middleware('can:show_students')->only('index');
        $this->middleware('can:create_students')->only('store');
        $this->middleware('can:edit_students')->only('update');
        $this->middleware('can:delete_students')->only('destroy');
    }

    public function index(Request $request)
    {
        return $this->student->index($request);
    }

    public function store(StudentRequest $request)
    {
        return $this->student->store($request);
    }

    public function update(StudentRequest $request, Student $student)
    {
        return $this->student->update($request, $student);
    }

    public function destroy(Student $student)
    {
        return $this->student->destroy($student);
    }

    public function search(Request $request)
    {
        return Student::where('name', 'like', "%{$request->q}%")
            ->select('id', 'name')
            ->limit(10)
            ->get();
    }

    public function storeAjax(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:students,email'],
            'phone' => ['nullable', 'string', 'max:25'],
            'password' => ['nullable', 'string', 'min:6', 'max:255'],
            'course_id' => 'nullable|exists:courses,id',
        ]);

        $password = $request->password;

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $password ? Hash::make($password) : null,
            'course_id' => $request->course_id,
        ]);

        return response()->json($student);
    }
}
