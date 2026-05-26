<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Role;

class RoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id'); // Assuming your route contains the voucher type ID as 'id'
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::in(Role::roles()),
                Rule::unique('roles', 'name')->ignore($id),
            ],
            'permissions' => 'required|array|min:1',
        ];
    }
}
