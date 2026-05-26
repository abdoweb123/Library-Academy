<?php

namespace App\Services\Admin;

use App\Http\Services\MainService;
use App\Models\Admin as ObjModel;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Book;
use App\Models\Person;
use App\Models\Section;
use App\Models\Borrowing;


class HomeService extends BaseService
{
    use MainService;

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index()
    {
        $data = $this->dashboard();

        // Return data to the view
        return view('admin.index', compact('data'));
    }

    public function dashboard()
    {
        // $request = request(); // 👈 هنا الحل

        // $ignore = $request->boolean('ignore_company_scope');
        // return Cache::remember('dashboard_data', 3600, function () {

            // 🔹 1. Counters
            $counts = [
                'books'        => Book::count(),

                'authors'      => Person::whereHas('books', function ($q) {
                    $q->where('book_person.role', 'author');
                })->count(),
            
                'translators'  => Person::whereHas('books', function ($q) {
                    $q->where('book_person.role', 'translator');
                })->count(),
            
                'sections'     => Section::count(),
            
                'borrowed_books'     => Borrowing::whereIn('status', ['borrowed', 'late'])->whereHas('bookCopy.book')->count(),
            ];

            // 🔹 2. Books per Section
            $booksPerSection = Section::withCount('books')
                ->orderBy('books_count', 'desc')
                ->get();

            // 🔹 3. Borrow Trend
            $trend = Borrowing::whereHas('bookCopy.book')->select(
                    DB::raw('DATE_FORMAT(borrow_date, "%Y-%m") as month'),
                    DB::raw('count(*) as total')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // 🔹 4. Top Borrowed Books
            $topBooks = Book::withCount('borrowings')
                ->orderByDesc('borrowings_count')
                ->limit(10)
                ->get();

            return [
                'counts'            => $counts,
                'booksPerSection'   => $booksPerSection,
                'trend'             => $trend,
                'topBooks'          => $topBooks,
            ];
        // });
    }


    public function updateFilterDate($request)
    {
        // Save and apply settings (live filter [ajax])
        $this->saveSearchSetting($request);

        return 'success';
    }


}
