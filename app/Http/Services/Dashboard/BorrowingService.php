<?php

namespace App\Http\Services\Dashboard;

use App\Http\Services\MainService;
use App\Models\BookCopy;
use App\Models\Student;
use App\Models\Book;
use Carbon\Carbon;

trait BorrowingService
{
    use MainService;

    public function modalInputs($model = null)
    {
        $studentOptions = Student::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        $copyOptions = BookCopy::query()
            ->with('book:id,title')
            ->latest()
            ->get()
            ->mapWithKeys(function ($copy) {
                $bookTitle = optional($copy->book)->title ?? 'Book';
                $label = $bookTitle . ' - ' . ($copy->book_code ?? ('#' . $copy->id));
                return [$copy->id => $label];
            })
            ->toArray();

        $inputs = [
            [
                'type' => 'hidden',
                'name' => 'id',
                'value' => $model ? $model->id : null,
                'show' => 0,
            ],
            [
                'type' => 'select',
                'name' => 'student_id',
                'label' => trns('student'),
                'options' => $studentOptions,
                'selected_options' => $model ? [$model->student_id] : [],
                'show' => 1,
            ],
            [
                'type' => 'select',
                'name' => 'book_copy_id',
                'label' => trns('book_copy'),
                'options' => $copyOptions,
                'selected_options' => $model ? [$model->book_copy_id] : [],
                'show' => 1,
            ],
            [
                'type' => 'date',
                'name' => 'borrow_date',
                'label' => trns('borrow_date'),
                'value' => $model?->borrow_date,
                'show' => 1,
            ],
            [
                'type' => 'date',
                'name' => 'due_date',
                'label' => trns('due_date'),
                'value' => $model?->due_date,
                'show' => 1,
            ],
            [
                'type' => 'date',
                'name' => 'return_date',
                'label' => trns('return_date'),
                'value' => $model?->return_date,
                'show' => 1,
            ],
            [
                'type' => 'select',
                'name' => 'status',
                'label' => trns('status'),
                'options' => [
                    'borrowed' => trns('borrowed'),
                    'returned' => trns('returned'),
                    'late' => trns('late'),
                ],
                'selected_options' => $model ? [$model->status] : ['borrowed'],
                'show' => 1,
            ],
        ];

        return $inputs;
    }

    public function modalVariables()
    {
        $variables = [
            'modal_dialog_width' => 'min-width: 45vw;',
            'cols' => 'col-md-6',
            'main_row' => 'justify-content-around px-4',
            'save_class' => 'mx-4',
        ];

        return $variables;
    }

    
    public function getAvailableBooks($selectedBookId = null)
    {
        $books = Book::query()
            ->when($selectedBookId, function ($q) use ($selectedBookId) {
                $q->orWhere('id', $selectedBookId);
            })
            ->where(function ($query) use ($selectedBookId) {
    
                $query->whereHas('copies', function ($q) {
                    $q->where('status', 'available')
                        ->whereDoesntHave('borrowings', function ($b) {
                            $b->whereIn('status', ['borrowed', 'late'])
                              ->whereNull('return_date');
                        });
                });
    
                // include selected book even if not available
                if ($selectedBookId) {
                    $query->orWhere('id', $selectedBookId);
                }
            })
            ->withCount([
                'copies as available_copies_count' => function ($query) {
                    $query->where('status', 'available')
                        ->whereDoesntHave('borrowings', function ($b) {
                            $b->whereIn('status', ['borrowed', 'late'])
                              ->whereNull('return_date');
                        });
                }
            ])
            ->orderBy('title')
            ->get();
    
        return $books;
    }


    public function calculateLateInfo($borrowing)
    {
        $lateDays = null;
        $lateMessage = null;

        $compareDate = $borrowing->return_date
            ? Carbon::parse($borrowing->return_date)
            : now();

        if (Carbon::parse($borrowing->due_date)->lt($compareDate)) {

            $lateDays = Carbon::parse($borrowing->due_date)
                ->startOfDay()
                ->diffInDays(Carbon::parse($compareDate)->startOfDay());

                if ($borrowing->return_date) {

                    $lateMessage = str_replace(
                        ':days',
                        $lateDays,
                        trns('returned_late_message')
                    );
                
                } else {
                
                    $lateMessage = str_replace(
                        ':days',
                        $lateDays,
                        trns('currently_late_message')
                    );
                }
        }

        return [
            'lateDays' => $lateDays,
            'lateMessage' => $lateMessage,
        ];
    }

}
