<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;


class BookRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'title' => 'required|string|max:255',
            'publisher_id' => 'nullable|exists:publishers,id',
            'shelf_if' => 'nullable|exists:shelves,id',
            'section_id' => 'nullable|exists:sections,id',

            'authors' => 'nullable|array',
            'authors.*' => 'exists:people,id',

            'translators' => 'nullable|array',
            'translators.*' => 'exists:people,id',

            'copies' => 'required|array|min:1',
            'copies.*.general_number' => 'required',
            'copies.*.book_code' => 'required',
        ];
    }

   


}
