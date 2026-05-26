<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\BookRequest;
use App\Models\Book;
use App\Repository\BookRepositoryInterface;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected $book;

    public function __construct(BookRepositoryInterface $book)
    {
        $this->book = $book;

        $this->middleware('can:show_books')->only('index');
        $this->middleware('can:create_books')->only('store');
        $this->middleware('can:edit_books')->only('update');
        $this->middleware('can:delete_books')->only('destroy');
    }

    public function index(Request $request)
    {
        return $this->book->index($request);
    }

    public function create()
    {
        return $this->book->create();
    }

    public function store(BookRequest $request)
    {     
        
        return $this->book->store($request);
    }

    public function edit(Book $book)
    {
        return $this->book->edit($book);
    }

    public function update(BookRequest $request, Book $book)
    {
        return $this->book->update($request, $book);
    }

    public function show(Book $book)
    {
        return $this->book->show($book);
    }

    public function destroy(Book $book)
    {
        return $this->book->destroy($book);
    }

    public function setActive(Request $request, $id)
    {
        return $this->book->setActive($request, $id);
    }

}
