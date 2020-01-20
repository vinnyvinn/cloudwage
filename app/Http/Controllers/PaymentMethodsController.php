<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentMethodsRequest;
use App\Policies\Policy;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Schema;
use Payroll\Models\EmployeePaymentMethods;
use Payroll\Models\PaymentMethod;
use Payroll\Models\PaymentMethodsFields;
use Payroll\Models\UDF;

class PaymentMethodsController extends Controller
{
    protected $payment_method;

    protected $UDF;

    /**
     * PaymentMethodsController constructor.
     *
     * @param PaymentMethod $payment_method
     * @param UDF           $UDF
     *
     */
    public function __construct(PaymentMethod $payment_method, UDF $UDF)
    {
        $this->payment_method = $payment_method;
        $this->UDF = $UDF;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Policy::canRead(new PaymentMethod());

        return view('modules.company.payment.methods.index')
            ->withMethods($this->payment_method->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Policy::canCreate(new PaymentMethod());

        return view('modules.company.payment.methods.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PaymentMethodsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentMethodsRequest $request)
    {
        Policy::canCreate(new PaymentMethod());
        $this->payment_method->addPaymentMethod($request->all());
        flash('Successfully added new Payment method', 'success');

        return redirect()->route('payment-methods.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Policy::canRead(new PaymentMethod());
        $method = $this->payment_method->findOrFail($id);
        $fields = $method->udfs;

        return view('modules.company.payment.methods.show')
            ->withMethod($method)
            ->withFields($fields);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Policy::canDelete(new PaymentMethod());
        if (! $this->payment_method->dropPaymentMethod($id)) {
            return redirect()->back()
                ->withErrors(
                    [
                        'message' => '<strong>Whoops!</strong><br> There are <strong>Employees</strong> currently assigned to this item. First unassign them then delete the item'
                    ]
                );
        }

        flash('Successfully deleted payment method', 'success');
        return redirect()->route('payment-methods.index');
    }

    public function edit($methodId)
    {
        Policy::canUpdate(new PaymentMethod());
        return view('modules.company.payment.methods.edit')
            ->withMethod(PaymentMethod::findOrFail($methodId));
    }

    public function update(Request $request, $methodId)
    {
        Policy::canUpdate(new PaymentMethod());
        PaymentMethod::findOrFail($methodId)->update($request->all());
        flash('Successfully edited payment method', 'success');

        return redirect()->route('payment-methods.index');
    }
}
