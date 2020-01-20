<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Payroll\Models\Policy;
use App\Policies\Policy as UserPolicy;

class PoliciesController extends Controller
{
    /**
     * @var Policy
     */
    private $policy;

    /**
     * PoliciesController constructor.
     *
     * @param Policy $policy
     */
    public function __construct(Policy $policy)
    {
        $this->policy = $policy;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        UserPolicy::canRead(new Policy());

        $policies = $this->policy->with(['module'])->orderBy('module_id')->get();
        $headers = $policies->unique('module_id');

        return view('modules.settings.policies.index')
            ->withHeaders($headers)
            ->withPolicies($policies);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        UserPolicy::canRead(new Policy());
        $policy = $this->policy->findOrFail($id);

        return view('modules.settings.policies.show')
            ->withPolicy($policy);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        UserPolicy::canUpdate(new Policy());
        $policy = $this->policy->findOrFail($id);

        return view('modules.settings.policies.edit')
            ->withPolicy($policy);
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
        UserPolicy::canUpdate(new Policy());
        $policy = $this->policy->findOrFail($id);
        $policy->fill($request->only(['value']));
        $policy->save();
        flash('Successfully updated policy.', 'success');

        return redirect()->route('policies.index');
    }
}
