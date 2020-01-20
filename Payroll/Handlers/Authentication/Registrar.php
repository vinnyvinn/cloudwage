<?php

namespace Payroll\Handlers\Authentication;

class Registrar
{

    public static function getRegister()
    {
        return view('auth.register');
    }

    public static function register($credentials = array())
    {

    }
}