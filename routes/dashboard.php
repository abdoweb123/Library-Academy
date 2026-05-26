<?php


use App\Http\Controllers\Dashboard\CompanyController;
use App\Http\Controllers\Dashboard\PersonController;
use App\Http\Controllers\Dashboard\SectionController;
use App\Http\Controllers\Dashboard\PublisherController;
use App\Http\Controllers\Dashboard\BookController;
use App\Http\Controllers\Dashboard\StudentController;
use App\Http\Controllers\Dashboard\BorrowingController;
use App\Http\Controllers\Dashboard\CourseController;
use App\Http\Controllers\Dashboard\LibraryStructureController;
use App\Http\Controllers\Dashboard\SectorController;
use App\Http\Controllers\Dashboard\RowController;
use App\Http\Controllers\Dashboard\CabinetController;
use App\Http\Controllers\Dashboard\ShelfController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;


Route::post('/toggle-sidebar', function () {
    session(['sidebarCollapsed' => !session('sidebarCollapsed', true)]);
    return response()->json(['status' => 'ok']);
})->name('toggle.sidebar');


Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        LocaleSessionRedirect::class,
        LaravelLocalizationRedirectFilter::class,
    ]
],
    function () {

        Route::group(['prefix'=>'dashboard', 'as'=>'dashboard.','middleware' => 'auth:admin'], function () {

            // Companies
            Route::resource('companies', CompanyController::class)->except('create','edit','show');
            Route::post('companies/set-active/{id}', [CompanyController::class, 'setActive'])->name('companies.setActive');

            // People
            Route::resource('people', PersonController::class)->except('create','edit','show');
            Route::post('people/set-active/{id}', [PersonController::class, 'setActive'])->name('people.setActive');
            Route::get('/authors/search', [PersonController::class, 'search'])->name('authors.search');
            Route::post('/authors/store-ajax', [PersonController::class, 'storeAjax'])->name('authors.storeAjax');

            // Sections
            Route::resource('sections', SectionController::class)->except('create','edit','show');
            Route::post('sections/set-active/{id}', [SectionController::class, 'setActive'])->name('sections.setActive');

            // Publishers
            Route::resource('publishers', PublisherController::class)->except('create','edit','show');
            Route::post('publishers/set-active/{id}', [PublisherController::class, 'setActive'])->name('publishers.setActive');
            Route::get('/publishers/search', [PublisherController::class, 'search'])->name('publishers.search');
            Route::post('/publishers/store-ajax', [PublisherController::class, 'storeAjax'])->name('publishers.storeAjax');

            // Books
            Route::resource('books', BookController::class);
            Route::post('book-copies', [BookController::class, 'bookCopies'])->name('book_copies');
            Route::post('books/set-active/{id}', [BookController::class, 'setActive'])->name('books.setActive');

            // Students
            Route::resource('students', StudentController::class)->except('create','edit','show');
            Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');
            Route::post('/students/store-ajax', [StudentController::class, 'storeAjax'])->name('students.storeAjax');

            // Borrowings
            Route::resource('borrowings', BorrowingController::class);
            Route::get('borrowing-books/{status?}', [BorrowingController::class, 'index'])->name('borrowing_books_by_status');

            // Courses
            Route::resource('courses', CourseController::class)->except('create','edit','show');
            Route::post('courses/set-active/{id}', [CourseController::class, 'setActive'])->name('courses.setActive');
            Route::get('/courses/search', [CourseController::class, 'search'])->name('courses.search');

            // search and store section
            Route::get('/sections/search', [SectionController::class, 'search'])->name('sections.search');
            Route::post('/sections/store-ajax', [SectionController::class, 'storeAjax'])->name('sections.storeAjax');

           
            // Library Structure
            Route::get('/library-structure', [LibraryStructureController::class, 'index'])->name('library_structure.index');
            Route::post('/sectors', [SectorController::class, 'store'])->name('sectors.store');
            Route::put('/sectors/{sector}', [SectorController::class, 'update'])->name('sectors.update');
            Route::post('/sectors/reorder', [SectorController::class, 'reorder'])->name('sectors.reorder');

            Route::post('/rows', [RowController::class, 'store'])->name('rows.store');
            Route::post('/cabinets', [CabinetController::class, 'store'])->name('cabinets.store');
            Route::post('/shelves', [ShelfController::class, 'store'])->name('shelves.store');

            Route::get('/locations/search', [LibraryStructureController::class, 'searchLocations'])->name('locations.search');

        });

    });





