<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
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
                Rule::unique('courses', 'name')->ignore($id),
            ],
//            'active' => ['required', Rule::in([0, 1])],
        ];
    }
}
