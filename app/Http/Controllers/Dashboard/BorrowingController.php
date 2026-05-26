<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\BorrowingRequest;
use App\Models\Borrowing;
use App\Repository\BorrowingRepositoryInterface;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    protected $borrowing;

    public function __construct(BorrowingRepositoryInterface $borrowing)
    {
        $this->borrowing = $borrowing;

        $this->middleware('can:show_borrowings')->only('index');
        $this->middleware('can:create_borrowings')->only('create', 'store');
        $this->middleware('can:edit_borrowings')->only('update');
        $this->middleware('can:delete_borrowings')->only('destroy');
    }

    public function index(Request $request)
    {
        return $this->borrowing->index($request);
    }

    public function create()
    {
        return $this->borrowing->create();
    }

    public function store(BorrowingRequest $request)
    {
        return $this->borrowing->store($request);
    }

    public function edit(Borrowing $borrowing)
    {
        return $this->borrowing->edit($borrowing);
    }

    public function update(BorrowingRequest $request, Borrowing $borrowing)
    {
        return $this->borrowing->update($request, $borrowing);
    }

    public function show(Borrowing $borrowing)
    {
        return $this->borrowing->show($borrowing);
    }

    public function destroy(Borrowing $borrowing)
    {
        return $this->borrowing->destroy($borrowing);
    }
}
