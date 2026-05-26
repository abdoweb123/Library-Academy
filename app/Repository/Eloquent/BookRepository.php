<?php

namespace App\Repository\Eloquent;

use App\Http\Services\CrudService;
use App\Http\Services\Dashboard\BookService;
use App\Http\Traits\FileManager;
use App\Models\Book;
use App\Models\User;
use App\Repository\BookRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;

class BookRepository extends Repository implements BookRepositoryInterface
{
    use BookService, FileManager, CrudService;

    protected Model $model;

    public function __construct(User $model)
    {
        parent::__construct($model);
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

    public function create()
    {
        return view('dashboard.books.form');
    }

    public function store($request)
    {
    //    return $request;

         // ✅ 2. إنشاء الكتاب
         $book = Book::create([
            'title' => $request->title,
            'section_id' => $request->section_id,
            'company_id' => getActiveCompany()->id,
        ]);

        // ✅ 3. حفظ authors + translators في pivot
        $people = [];

        // Authors
        foreach ($request->authors ?? [] as $id) {
            $people[$id] = ['role' => 'author'];
        }

        // Translators
        foreach ($request->translators ?? [] as $id) {
            $people[$id] = ['role' => 'translator'];
        }

        if (!empty($people)) {
            $book->people()->sync($people);
        }

        // ✅ 4. حفظ النسخ (Copies)
        foreach ($request->copies as $copy) {

            $book->copies()->create([
                'publisher_id' => $request->publisher_id, // أو لو هتخليها per copy
                'publish_year' => $request->publish_year,
                'shelf_id' => $request->location_id,

                'pages' => $request->pages,
                'size' => $request->size,

                'general_number' => $copy['general_number'],
                'classification_number' => $copy['classification_number'] ?? null,
                'shelf_order' => $copy['shelf_order'] ?? null,
                'book_code' => $copy['book_code'],
                'volumes_number' => $copy['volumes_number'] ?? null,
            ]);
        }

        // ✅ 5. Redirect
        return redirect()
            ->route('dashboard.books.index')
            ->with('success', trns('created_successfully'));    
    }

    public function edit($book)
    {
        $book_copies = $book->copies;    
        return view('dashboard.books.form', compact('book','book_copies'));
    }

    public function update($request, $book)
    {
        // return $request;
    
        $book->update([
            'title' => $request->title,
            'publisher_id' => $request->publisher_id,
            'section_id' => $request->section_id,
        ]);

        // 👥 people
        $people = [];

        foreach ($request->authors ?? [] as $id) {
            $people[$id] = ['role' => 'author'];
        }

        foreach ($request->translators ?? [] as $id) {
            $people[$id] = ['role' => 'translator'];
        }

        $book->people()->sync($people);

        // 📚 copies (حل بسيط: delete + recreate)
        $book->copies()->delete();

        foreach ($request->copies as $copy) {

            $book->copies()->create([
                'publisher_id' => $request->publisher_id, // أو لو هتخليها per copy
                'publish_year' => $request->publish_year,

                'pages' => $request->pages,
                'size' => $request->size,

                'general_number' => $copy['general_number'],
                'classification_number' => $copy['classification_number'] ?? null,
                'shelf_number' => $copy['shelf_number'] ?? null,
                'book_code' => $copy['book_code'],
                'volumes_number' => $copy['volumes_number'] ?? null,
            ]);
        }

        return redirect()->back()->with('success', trns('Updated Successfully'));
    }

    public function show($book)
    {
        $book_copies = $book->copies;    
        return view('dashboard.books.form', compact('book','book_copies'));
    }

    public function destroy($book)
    {
        $book->delete();
    }

    public function setActive($request, $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        // إذا كان غير مفعل، فعّله واجعل الباقي غير مفعل
        if ($book->active == 0) {
            $book->active = 1;
            $book->save();
            return response()->json(['success' => true, 'active' => 1]);
        }
        
        $book->active = 0;
        $book->save();

        // إذا كان مفعل بالفعل، لا تفعل شيء (التحكم في JS)
        return response()->json(['success' => false, 'active' => 1]);
    }

}
