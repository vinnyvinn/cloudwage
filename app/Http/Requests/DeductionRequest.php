<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DeductionRequest extends Request
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
                'name' => 'required|unique:deductions,name,' . $this->deductions,
                'threshold' => 'required',
                'type' => 'required',
                'has_relief' => 'required',
            ];
        }

        return [
            'name' => 'required|unique:deductions',
            'threshold' => 'required',
            'type' => 'required',
            'has_relief' => 'required',
        ];
    }
}
