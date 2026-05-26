<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'internal_shipping_cost'=>'numeric',
            'external_shipping_cost'=>'numeric',
        ];
    }
}
