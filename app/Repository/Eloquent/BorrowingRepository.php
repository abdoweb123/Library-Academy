<?php

namespace App\Repository\Eloquent;

use App\Http\Services\CrudService;
use App\Http\Services\Dashboard\BorrowingService;
use App\Http\Traits\FileManager;
use App\Models\BookCopy;
use App\Models\Borrowing;
use App\Models\User;
use App\Repository\BorrowingRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class BorrowingRepository extends Repository implements BorrowingRepositoryInterface
{
    use BorrowingService, FileManager, CrudService;

    protected Model $model;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function index($request)
    {
        // Update all late borrowings
        Borrowing::whereNull('return_date')
        ->whereDate('due_date', '<', Carbon::today())
        ->where('status', 'borrowed')
        ->update([
            'status' => 'late'
        ]);


        $editRoute = 'dashboard.borrowings.update';
        $deleteRoute = 'dashboard.borrowings.destroy';

        $status = $request->status ?? null;

        $query = Borrowing::query()
                ->with(['student:id,name', 'bookCopy.book:id,title'])
                ->whereHas('bookCopy.book');

        // filter by status
        if ($status && method_exists(Borrowing::class, 'scope'.ucfirst($status))) {
            $query->{$status}();
            // return $status;
        }

        $models = $query->latest()->get();

      

        if ($request->ajax()) {
            return DataTables::of($models)
                ->addIndexColumn()
                ->addColumn('student', function ($model) {
                    return optional($model->student)->name;
                })
                ->addColumn('status', function ($model) {

                    $status = $model->status;
                
                    $colors = [
                        'borrowed' => 'warning',  // أصفر
                        'late'     => 'danger',   // أحمر
                        'returned' => 'success',  // أخضر
                    ];
                
                    $color = $colors[$status] ?? 'secondary';
                
                    return '<span class="badge bg-' . $color . '">'
                            . trns($status) .
                           '</span>';
                })
                ->addColumn('book_copy', function ($model) {
                    $bookTitle = optional(optional($model->bookCopy)->book)->title;
                    $bookCode = optional($model->bookCopy)->book_code;
                    if (!$bookTitle && !$bookCode) {
                        return '-';
                    }
                    return trim(($bookTitle ?? ''));
                })
                ->addColumn('return_date', function ($model) {
                    return $model->return_date ?: '-';
                })
                ->addColumn('action', function ($model) use($deleteRoute){

                    $buttons = '';
                
                    // show
                    if (auth()->user()->can('show_'.$model->getTable())) {
                        $buttons .= '<a href="'.route('dashboard.borrowings.show', $model->id).'"
                                        class="btn text-primary btn-sm mx-0 px-0">
                                        <i class="fas fa-eye"></i>
                                    </a>';
                    }
                
                    // edit
                    if (auth()->user()->can('edit_'.$model->getTable())) {
                        $buttons .= '<a href="'.route('dashboard.borrowings.edit', $model->id).'"
                                        class="btn text-black btn-sm mx-0 px-0">
                                        <i class="fas fa-edit"></i>
                                    </a>';
                    }
                
                    // delete
                    if (auth()->user()->can('delete_'.$model->getTable())) {

                        $buttons .= view('dashboard.borrowings.delete', [
                            'model' => $model,
                            'deleteRoute' => $deleteRoute,
                        ])->render();
                    }
                
                    return $buttons;
                })
                ->rawColumns(['student','stautus', 'book_copy', 'status', 'action'])
                ->make(true);
        }

        return view('dashboard.borrowings.index', compact('models'));
    }

    public function create()
    {
        $books = $this->getavailableBooks();

        return view('dashboard.borrowings.form', compact('books'));
    }

    public function store($request)
    {
    //   return $request->all(); 
       $data = $request->validated();

        if (empty($data['book_copy_id']) && !empty($data['book_id'])) {
            $firstAvailableCopy = BookCopy::query()
                ->where('book_id', $data['book_id'])
                ->where('status', 'available')
                ->whereDoesntHave('borrowings', function ($borrowingQuery) {
                    $borrowingQuery
                        ->whereIn('status', ['borrowed', 'late'])
                        ->whereNull('return_date');
                })
                ->orderBy('id')
                ->first();

            if (!$firstAvailableCopy) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['book_id' => trns('no_available_copy_for_book')]);
            }

            $data['book_copy_id'] = $firstAvailableCopy->id;
        }

        unset($data['book_id']);

        Borrowing::create($data);

        $data['status'] = 'borrowed';

        if (in_array($data['status'], ['borrowed', 'late'])) {
            BookCopy::where('id', $data['book_copy_id'])->update(['status' => 'borrowed']);
        }

        if ($request->ajax()) {
            return response()->json(['success' => trns('created_successfully')]);
        }

        return redirect()
            ->route('dashboard.borrowings.index')
            ->with('success', trns('created_successfully'));
    }

    public function edit($borrowing)
    {
        $borrowing->load(['student', 'bookCopy.book']);

        $books = $this->getAvailableBooks($borrowing->bookCopy->book_id);

        $lateData = $this->calculateLateInfo($borrowing);

        
        // return view('dashboard.borrowings.form', compact('borrowing', 'books', 'lateDays', 'lateMessage'));
        return view('dashboard.borrowings.form', [
            'borrowing' => $borrowing,
            'books' => $books,
            'lateDays' => $lateData['lateDays'],
            'lateMessage' => $lateData['lateMessage'],
        ]);
    }

    public function update($request, $borrowing)
    {
        $data = $request->validated();

        // ❌ تجاهل أي بيانات أخرى
        $allowed = [
            'return_date',
            'status',
        ];

        $updateData = array_intersect_key($data, array_flip($allowed));

        // تحديث الاستعارة فقط
        $borrowing->update($updateData);

        // 🔥 تحديث حالة النسخة حسب status
        if (isset($updateData['status'])) {

            if (in_array($updateData['status'], ['borrowed', 'late'])) {
                $borrowing->bookCopy->update(['status' => 'borrowed']);
            }

            if ($updateData['status'] === 'returned') {
                $borrowing->bookCopy->update(['status' => 'available']);
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => trns('updated_successfully')]);
        }

        return redirect()
            ->route('dashboard.borrowings.index')
            ->with('success', trns('updated_successfully'));
    }
    public function show($borrowing)
    {
        $borrowing->load(['student.course', 'bookCopy.book']);
        $books = $this->getAvailableBooks($borrowing->bookCopy->book_id);
        $lateData = $this->calculateLateInfo($borrowing);

        // return view('dashboard.borrowings.form', compact('borrowing','books'));
        return view('dashboard.borrowings.form', [
            'borrowing' => $borrowing,
            'books' => $books,
            'lateDays' => $lateData['lateDays'],
            'lateMessage' => $lateData['lateMessage'],
        ]);
    }

    public function destroy($borrowing)
    {
        $borrowing->bookCopy->update(['status' => 'available']);
        $borrowing->delete();
    }

    
}
