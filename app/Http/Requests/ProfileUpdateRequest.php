<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class ProfileUpdateRequest extends Request
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
            'email' => 'email|required|unique:users,id,' . Sentinel::getUser()->id,
            'old_password' => 'required',
            'password' => 'required|confirmed'
        ];
    }
}
