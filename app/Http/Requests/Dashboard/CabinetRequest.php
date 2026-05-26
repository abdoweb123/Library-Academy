<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CabinetRequest extends FormRequest
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
                Rule::unique('cabinets', 'name')->ignore($id),
            ],

            'row_id' => [
                'required',
                'exists:rows,id',
            ],
        ];
    }
}
