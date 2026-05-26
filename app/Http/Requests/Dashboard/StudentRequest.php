<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('students', 'name')->ignore($id),
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('students', 'email')->ignore($id),
            ],
            'password' => ['nullable', 'string', 'min:6', 'max:255'],
            'phone' => ['nullable', 'string', 'max:25'],
            'course_id' => 'required|exists:courses,id',
        ];
    }
}
