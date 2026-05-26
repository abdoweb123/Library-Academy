<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\PersonRequest;
use App\Models\Person;
use App\Repository\PersonRepositoryInterface;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    protected $person;

    public function __construct(PersonRepositoryInterface $person)
    {
        $this->person = $person;

        $this->middleware('can:show_people')->only('index');
        $this->middleware('can:create_people')->only('store');
        $this->middleware('can:edit_people')->only('update');
        $this->middleware('can:delete_people')->only('destroy');
    }

    public function index(Request $request)
    {
        return $this->person->index($request);
    }

    public function store(PersonRequest $request)
    {
        return $this->person->store($request);
    }

    public function update(PersonRequest $request, Person $person)
    {
        return $this->person->update($request, $person);
    }

    public function destroy(Person $person)
    {
        return $this->person->destroy($person);
    }

    public function setActive(Request $request, $id)
    {
        return $this->person->setActive($request, $id);
    }

    public function search(Request $request)
    {
        return Person::where('name', 'like', "%{$request->q}%")
            ->select('id', 'name')
            ->limit(10)
            ->get();
    }
    
    public function storeAjax(Request $request)
    {
        $author = Person::create([
            'name' => $request->name
        ]);

        return response()->json($author);
    }
}
