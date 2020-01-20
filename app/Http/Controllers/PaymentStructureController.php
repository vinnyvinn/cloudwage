<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentStructureRequest;
use App\Policies\Policy;
use Illuminate\Http\Request;

use App\Http\Requests;
use Payroll\Models\PaymentStructure;

class PaymentStructureController extends Controller
{
    protected $structure;

    /**
     * PaymentStructureController constructor.
     *
     * @param PaymentStructure $structure
     *
     */
    public function __construct(PaymentStructure $structure)
    {
        $this->structure = $structure;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Policy::canRead(new PaymentStructure());

        return view('modules.company.payment.structures.index')
            ->withStructures($this->structure->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Policy::canCreate(new PaymentStructure());

        return view('modules.company.payment.structures.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PaymentStructureRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentStructureRequest $request)
    {
        Policy::canCreate(new PaymentStructure());

        $this->structure->create($request->all());
        flash('Successfully added new Payment Structure', 'success');

        return redirect()->route('payment-structures.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Policy::canRead(new PaymentStructure());

        return view('modules.company.payment.structures.show')
            ->withStructure($this->structure->findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Policy::canUpdate(new PaymentStructure());

        return view('modules.company.payment.structures.edit')
            ->withStructure($this->structure->findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PaymentStructureRequest $request
     * @param  int                 $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentStructureRequest $request, $id)
    {
        Policy::canUpdate(new PaymentStructure());

        $type = $this->structure->findOrFail($id);
        $type->fill($request->all());
        flash('Successfully edited Payment Structure', 'success');

        return redirect()->route('payment-structures.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Policy::canDelete(new PaymentStructure());

        $type = $this->structure->findOrFail($id);

        if (count($type->employeeTypes) > 0) {
            return redirect()->back()
                ->withErrors(
                    [
                        'message' => '<strong>Whoops!</strong><br> There are <strong>Employee Types</strong> currently assigned to this item. First unassign them then delete the item'
                    ]
                );
        }
        $type->delete();
        flash('Successfully deleted Payment Structure', 'success');

        return redirect()->route('payment-structures.index');
    }
}
