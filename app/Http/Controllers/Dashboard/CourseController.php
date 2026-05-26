<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CourseRequest;
use App\Models\Course;
use App\Repository\CourseRepositoryInterface;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected $course;

    public function __construct(CourseRepositoryInterface $course)
    {
        $this->course = $course;

        $this->middleware('can:show_courses')->only('index');
        $this->middleware('can:create_courses')->only('store');
        $this->middleware('can:edit_courses')->only('update');
        $this->middleware('can:delete_courses')->only('destroy');
    }

    public function index(Request $request)
    {
        return $this->course->index($request);
    }

    public function store(CourseRequest $request)
    {
        return $this->course->store($request);
    }

    public function update(CourseRequest $request, Course $course)
    {
        return $this->course->update($request, $course);
    }

    public function destroy(Course $course)
    {
        return $this->course->destroy($course);
    }

    public function setActive(Request $request, $id)
    {
        return $this->course->setActive($request, $id);
    }

    public function search(Request $request)
    {
        return Course::where('name', 'like', "%{$request->q}%")
            ->select('id', 'name')
            ->limit(10)
            ->get();
    }

}
