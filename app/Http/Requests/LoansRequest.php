<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class LoansRequest extends Request
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
        return [
            "employee_id" => "required",
            "amount" => "required",
            "rate" => "required",
            "type" => "required",
            "iterations" => "required",
            "duration" => "required"
        ];
    }
}
