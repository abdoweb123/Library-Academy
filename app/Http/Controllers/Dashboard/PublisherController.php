<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\PublisherRequest;
use App\Models\Publisher;
use App\Repository\PublisherRepositoryInterface;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    protected $publisher;

    public function __construct(PublisherRepositoryInterface $publisher)
    {
        $this->publisher = $publisher;

        $this->middleware('can:show_publishers')->only('index');
        $this->middleware('can:create_publishers')->only('store');
        $this->middleware('can:edit_publishers')->only('update');
        $this->middleware('can:delete_publishers')->only('destroy');
    }

    public function index(Request $request)
    {
        return $this->publisher->index($request);
    }

    public function store(PublisherRequest $request)
    {
        return $this->publisher->store($request);
    }

    public function update(PublisherRequest $request, Publisher $publisher)
    {
        return $this->publisher->update($request, $publisher);
    }

    public function destroy(Publisher $publisher)
    {
        return $this->publisher->destroy($publisher);
    }

    public function setActive(Request $request, $id)
    {
        return $this->publisher->setActive($request, $id);
    }

    public function search(Request $request)
    {
        return Publisher::where('name', 'like', "%{$request->q}%")
            ->select('id', 'name')
            ->limit(10)
            ->get();
    }

    public function storeAjax(Request $request)
    {
        $publisher = Publisher::create([
            'name' => $request->name
        ]);

        return response()->json($publisher);
    }
    
}
