<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Payroll\Models\CompanyProfile;
use Payroll\Models\Relief;

class ReliefController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currency = CompanyProfile::first()->currency;

        return view('modules.company.reliefs.index')
            ->withCurrency($currency->code)
            ->withReliefs(Relief::all());
    }
}
