<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

function makeSlug($inputString, $compareColumn, Model $model)
{
    $slug = Str::slug($inputString);
    $count = $model->whereRaw($compareColumn . " RLIKE '^{$slug}(-[0-9]+)?$'")->count();
    return $count ? "{$slug}-{$count}" : $slug;
}

function generateView(Request $request, $view)
{
    return view($request->ajax() ? 'ajax.' . $view : $view);
}

function flash($message, $status)
{
    session()->flash('flash_message', '<strong>Payroll</strong><br>' . $message);
    session()->flash('flash_message_status', $status);
}

function generatePDF($view, $orientation = 'portrait')
{
    $pdf = new DOMPDF();
    $pdf->set_option('enable_remote', true);
    $pdf->load_html($view->render());
    $pdf->set_paper('A4', $orientation);
    $pdf->render();

    header('Content-Type: application/pdf');
    return $pdf->stream('payroll.pdf', ['Attachment' => 0]);
}

function database()
{
    if (! isset($_SERVER['HTTP_HOST'])) {
        return env('DB_DATABASE', 'payroll');
    }

    $pieces = explode('.', request()->getHost());

    if (count($pieces) < 3) {
        return env('DB_DATABASE');
    }

    return 'cloudwag_' . $pieces[0];
}

function currentSubdomain()
{
    if (! isset($_SERVER['HTTP_HOST'])) {
        return env('DB_DATABASE', 'payroll');
    }

    $pieces = explode('.', request()->getHost());

    if (count($pieces) < 3) {
        return env('DB_DATABASE');
    }

    return $pieces[0];
}

function getSuffix($number)
{
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if (($number %100) >= 11 && ($number%100) <= 13) {
        return 'th';
    }
        return $ends[$number % 10];
}