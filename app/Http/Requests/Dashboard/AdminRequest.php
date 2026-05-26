<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        $adminId = $this->route('admin'); // This retrieves the 'admin' parameter from the route

        return [
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('admins', 'email')->ignore($adminId),
            ],
            'phone' => 'required',
            'role_id' => 'required|exists:roles,id',
            'password' => $this->isMethod('post') ? 'required|min:6' : 'nullable|min:6',
        ];

    }

    public function messages()
    {
        return [
            'email.unique' => trns('email_already_used'),
        ];
    }

} //end of class
