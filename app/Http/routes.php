<?php

//Route::group(['domain' => 'subdomain.payroll.app'], function () {
//    Route::get('/', function () {
//        return view('welcome');
//    });
//    Route::get('/me', function (CoinageCalculator $calculator) {
////        abort(400, 'hrhryyuy');
//        return response('hege', 400);
//        $values = [1000,500,200,100,50,40,20,10,5,1];
//        dd($calculator->withAmount(68489019)->withDivisions($values)->get());
////        $amount = 68489019;
////        $thousands = explode('.', $amount / 1000);
////        $fiveHundreds = explode('.', $thousands[1] / 500);
//
////        var_dump('Total:' . number_format($amount, 2));
//        dd(getAmount($amount, $values));
//    });
//});
//
//function getAmount($amount, $value, $step = 0, $coinage = [])
//{
//    $result = explode('.', $amount / $value[$step]);
//    $remainder = $amount - ($value[$step] * $result[0]);
//    $coinage [] = [
//        $value[$step] => $result[0]
//    ];
//
//    $step++;
//    if ($step < count($value)) {
//        return getAmount($remainder, $value, $step, $coinage);
//    }
//
//    return $coinage;
//}


use Payroll\Models\Payroll;
use Payroll\Repositories\EmployeeRepository;
use Payroll\Repositories\HolidayRepository;
use Payroll\Repositories\PolicyRepository;

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', ['as' => 'dashboard', 'uses' => 'PagesController@dashboard']);
    Route::get('logout', ['as' => 'logout', 'uses' => 'Authentication\AuthController@logout']);
    Route::get('user-profile', ['as' => 'user.profile', 'uses' => 'UsersController@profile']);
    Route::post('user-profile', ['as' => 'user.profile.update', 'uses' => 'UsersController@postProfile']);
    Route::resource('profile', 'CompanyProfileController');
    Route::resource('departments', 'DepartmentsController');
    Route::get('department-print/{department}', ['as' => 'departments.generate', 'uses' => 'DepartmentsController@generate']);
    Route::post('department-print', ['as' => 'departments.pdfs', 'uses' => 'DepartmentsController@getPDF']);
    Route::resource('allowances', 'AllowancesController');
    Route::resource('employee-allowances', 'EmployeeAllowancesController');
    Route::get('allowances-print/{id}', ['as' => 'allowances.generate', 'uses' => 'AllowancesController@generate']);
    Route::post('allowances-print', ['as' => 'allowances.document', 'uses' => 'AllowancesController@getDocument']);
    Route::resource('deductions', 'DeductionsController');
    Route::resource('employee-deductions', 'EmployeeDeductionsController');
    Route::get('deductions-report/{id}', ['as' => 'deductions.report', 'uses' => 'DeductionsController@report']);
    Route::post('deductions-print/{id}', ['as' => 'deductions.generate', 'uses' => 'DeductionsController@generate']);
    Route::post('deductions-print', ['as' => 'deductions.document', 'uses' => 'DeductionsController@getDocument']);
    Route::resource('reliefs', 'ReliefController');
    Route::resource('employee-types', 'EmployeeTypesController');
    Route::resource('payment-methods', 'PaymentMethodsController');
    Route::resource('payment-structures', 'PaymentStructureController');
    Route::resource('shifts', 'ShiftsController');
    Route::resource('work-plans', 'WorkPlanController');
    Route::resource('grades', 'PayGradesController');
    Route::resource('holidays', 'HolidaysController');
    Route::resource('employees', 'EmployeesController');
    Route::resource('contracts', 'EmployeeContractsController');
    Route::resource('assignments', 'EmployeeAssignmentsController');
    Route::resource('employee-payment-methods', 'EmployeePaymentMethodsController');
    //overtime
    Route::resource('overtime', 'OTController');
    Route::resource('holiday-pay', 'HolidaysPayController');
    Route::resource('leave-pay', 'LeavePayController');
    Route::resource('general', 'SettingsController');
        //
    Route::resource('leave', 'LeaveController');
    Route::get('leave-total', 'LeaveController@total');
    Route::get('kra-imports', ['as' => 'kra.imports', 'uses' => 'KraController@kraImports']);
    Route::post('kra-imports-generate', ['as' => 'kra.generate', 'uses' => 'KraController@generate']);
    Route::resource('payroll', 'PayrollController');
    Route::get('finalize-payroll', 'PayrollController@finalize');
    Route::get('delete-payroll', 'PayrollController@deletePayrolls');
    Route::get('payroll-pdf/{id}', ['as' => 'payroll.pdf', 'uses' => 'PayrollController@getPDF']);
     Route::get('eft', ['as' => 'eft.index', 'uses' => 'EftReportController@index']);
    Route::post('eft-report', ['as' => 'eft.report', 'uses' => 'EftReportController@report']);
    Route::get('statutoryFiles', 'ReportsController@statutoryFiles')->name('statutory-files');
Route::post('export-statutory', 'ReportsController@exportDocument')->name('statutory-export');
    Route::get('payroll-pdfs', ['as' => 'payroll.pdfs', 'uses' => 'PayrollController@getAllPDFs']);
    Route::get('payroll-view/{month}', ['as' => 'payroll.show.all', 'uses' => 'PayrollController@viewAll']);
    Route::get('payroll-report', ['as' => 'payroll.report', 'uses' => 'PayrollController@report']);
    Route::post('payroll-generate', ['as' => 'payroll.generate', 'uses' => 'PayrollController@generate']);
    Route::post('payroll-print', ['as' => 'payroll.document', 'uses' => 'PayrollController@getDocument']);
    Route::resource('advances', 'AdvancesController');
    Route::get('advances-bulk', ['as' => 'advances.bulk', 'uses' => 'AdvancesController@bulkAssign']);
    Route::post('advances-bulk', ['as' => 'advances.process', 'uses' => 'AdvancesController@bulkProcess']);
    Route::get('loans/details/{loanId}', ['as' => 'loans.details', 'uses' => 'LoansController@details']);
    Route::post('payroll-pdfs', ['as' => 'payroll.pdfs', 'uses' => 'PayrollController@getAllPDFs']);
    Route::resource('loans', 'LoansController');
    Route::resource('policies', 'PoliciesController');
    Route::resource('days-off', 'DaysOffController');
    Route::get('print-payslips', ['as' => 'print.payslips', 'uses' => 'PayrollController@print_payslips_batch']);
//    Route::resource('tax', 'TaxReportsController');
    Route::get('tax', ['as' => 'tax.index', 'uses' => 'TaxReportsController@index']);
    Route::get('tax-report', 'TaxReportsController@getYear');
    Route::post('tax/{type}', ['as' => 'tax.show', 'uses' => 'TaxReportsController@getReport']);
    Route::resource('template', 'ReportTemplateController');
    Route::resource('worked', 'DaysWorkedController');
    Route::resource('hours-worked', 'HoursWorkedController');
    Route::resource('units-made', 'UnitsMadeController');
    Route::resource('coinage', 'CoinageController');
    Route::resource('users', 'UsersController');
});


Route::get('login', ['as' => 'login.index', 'uses' => 'Authentication\AuthController@getLogin']);
Route::post('login', ['as' => 'login.store', 'uses' => 'Authentication\AuthController@postLogin']);
Route::get('forgot', ['as' => 'forgot.index', 'uses' => 'Authentication\AuthController@index']);
Route::post('forgot', ['as' => 'forgot.store', 'uses' => 'Authentication\AuthController@index']);
