<?php

namespace App\Http\Controllers;

use App\Policies\Policy;
use Illuminate\Http\Request;

use App\Http\Requests;
use Payroll\Models\Employee;
use Payroll\Models\EmployeePaymentMethods;
use Payroll\Models\PaymentMethod;

class EmployeePaymentMethodsController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Policy::canUpdate(new Employee());
        $payments = EmployeePaymentMethods::with('employee')->findOrFail($id);

        return view('modules.employees.payment-methods.edit')
            ->withPayment($payments)
            ->withPaymentMethods(PaymentMethod::all());
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Policy::canUpdate(new Employee());
        $payments = EmployeePaymentMethods::with('employee')->findOrFail($id);
        $payments->clearAttributes();
        $payments->fill($request->all());
        $method = PaymentMethod::with('udfs')->findOrFail($request->get('payment_method_id'));
        $payments->updateWithUdfs($method->udfs, $request->all());
        $payments->save();
        flash('Successfully updated employee\'s payment method', 'success');

        return redirect()->route('employees.show', $payments->employee->id);
    }
}
