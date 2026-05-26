<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PublisherRequest extends FormRequest
{
    public function publisherize()
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
                Rule::unique('publishers', 'name')->ignore($id),
            ],
        ];
    }
}
