<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\BookRequest;
use App\Models\Book;
use App\Models\BookCopy;
use App\Repository\BookRepositoryInterface;
use Illuminate\Http\Request;

class BookController extends Controller
{
 
    public function getBookCopies()
    {
        $bookCopies = BookCopy::with('book')->get();
         
    }

    public function index($request)
    {
        $createRoute = 'dashboard.books.store';
        $editRoute = 'dashboard.books.update';
        $deleteRoute = 'dashboard.books.destroy';

        $models = Book::query()->with('people')->get();

        if ($request->ajax()) {
            return DataTables::of($models)
                ->addIndexColumn()
                ->addColumn('action', function ($model) use($deleteRoute){

                    $buttons = '';
                
                    // show
                    if (auth()->user()->can('show_'.$model->getTable())) {
                        $buttons .= '<a href="'.route('dashboard.books.show', $model->id).'"
                                        class="btn text-primary btn-sm mx-0 px-0">
                                        <i class="fas fa-eye"></i>
                                    </a>';
                    }
                
                    // edit
                    if (auth()->user()->can('edit_'.$model->getTable())) {
                        $buttons .= '<a href="'.route('dashboard.books.edit', $model->id).'"
                                        class="btn text-black btn-sm mx-0 px-0">
                                        <i class="fas fa-edit"></i>
                                    </a>';
                    }
                
                    // delete
                    if (auth()->user()->can('delete_'.$model->getTable())) {

                        $buttons .= view('dashboard.books.delete', [
                            'model' => $model,
                            'deleteRoute' => $deleteRoute,
                        ])->render();
                    }
                
                    return $buttons;
                })
                ->rawColumns(['action'])
               
                ->addColumn('author_name', function ($model) {
                    return $model->authors_names;
                })
                ->addColumn('active', function ($model) {
                    $checked = $model->active ? 'checked' : '';
                    return '
                    <input type="checkbox" class="toggle-active"
                                data-table="books"
                                data-id="'.$model->id.'"
                                '.$checked.'
                                data-toggle="toggle"
                                data-on="'.trns('active').'"
                                data-off="'.trns('inactive').'"
                                data-onstyle="success"
                                data-offstyle="danger">
                    ';
                })
                ->rawColumns(['title','active','author_name','action'])
                ->make(true);
        }

        $inputs = $this->modalInputs();
        $variables = $this->modalVariables();

        return view('dashboard.books.index', compact('models','inputs','variables','createRoute'));
    }

    
}
