<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonRequest extends FormRequest
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
                Rule::unique('people', 'name')->ignore($id),
            ],
        ];
    }
}
