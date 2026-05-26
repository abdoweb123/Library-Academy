<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class BorrowingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    // public function rules()
    // {
    //     $isEdit = $this->isMethod('put') || $this->isMethod('patch');


    //     return [
    //         if(!$isEdit){
    //             'student_id' => ['required', 'exists:students,id'],
    //             'book_id' => ['nullable', 'required_without:book_copy_id', 'exists:books,id'],
    //             'book_copy_id' => ['nullable', 'exists:book_copies,id'],
    //             'borrow_date' => ['required', 'date'],
    //             'due_date' => ['nullable', 'date', 'after_or_equal:borrow_date'],
    //         }

    //         'return_date' => array_merge(
    //         $isEdit
    //             ? ['required', 'date', 'after_or_equal:borrow_date']
    //             : ['nullable', 'date', 'after_or_equal:borrow_date']
    //     ),

    //     'status' => $isEdit
    //         ? ['required', 'in:borrowed,returned,late']
    //         : ['nullable', 'in:borrowed,returned,late'],
    //     ];
    // }

    public function rules()
    {
        $isEdit = $this->isMethod('put') || $this->isMethod('patch');

        $rules = [];

        if (!$isEdit) {
            $rules['student_id'] = ['required', 'exists:students,id'];
            $rules['book_id'] = ['nullable', 'required_without:book_copy_id', 'exists:books,id'];
            $rules['book_copy_id'] = ['nullable', 'exists:book_copies,id'];
            $rules['borrow_date'] = ['required', 'date'];
            $rules['due_date'] = ['nullable', 'date', 'after_or_equal:borrow_date'];
        }

        $rules['return_date'] = $isEdit
            ? ['required', 'date', 'after_or_equal:borrow_date']
            : ['nullable', 'date', 'after_or_equal:borrow_date'];

        $rules['status'] = $isEdit
            ? ['required', 'in:borrowed,returned,late']
            : ['nullable', 'in:borrowed,returned,late'];

        return $rules;
    }
}
