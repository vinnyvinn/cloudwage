<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PaymentStructureRequest extends Request
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
                'name' => 'required | unique:payment_structures,name,' . $this->payment_structures,
                'description' => 'required'
            ];
        }
        return [
            'name' => 'required | unique:payment_structures',
            'description' => 'required'
        ];
    }
}
