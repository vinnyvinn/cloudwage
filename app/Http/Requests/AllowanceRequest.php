<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AllowanceRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
                'currency_id' => 'required|numeric',
                'has_relief' => 'required|numeric',
            ];

        if ($this->method() == 'PUT') {
            $rules [] = ['name' => 'required|unique:allowances,name,' . $this->allowances];

            return $rules;
        }

        $rules [] = ['name' => 'required|unique:allowances'];

        return $rules;
    }
}
