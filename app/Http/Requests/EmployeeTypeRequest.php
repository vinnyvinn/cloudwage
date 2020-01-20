<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EmployeeTypeRequest extends Request
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
        if ($this->method() == 'PUT') {
            return [
                'name' => 'required|min:3| unique:employee_types,name,' . $this->employee_types,
                'payment_structure_id' => 'required|numeric',
                'description' => 'required|min:20'
            ];
        }

        return [
            'name' => 'required | unique:employee_types',
            'payment_structure_id' => 'required|numeric',
            'description' => 'required'
        ];
    }
}
