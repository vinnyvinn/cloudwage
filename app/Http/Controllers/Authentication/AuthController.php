<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Payroll\Handlers\Authentication\Authenticator;
use Payroll\Requests\LoginRequest;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function getLogin()
    {
        return Authenticator::getLogin();
    }

    public function postLogin(LoginRequest $request)
    {
        $credentials = $request->only(['username', 'password']);

        return (new Authenticator($request))->authenticate($credentials, $request->has('remember'));
    }

    public function logout()
    {
        return Authenticator::logout();
    }
}
