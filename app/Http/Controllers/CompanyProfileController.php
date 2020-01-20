<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyProfileRequest;
use App\Policies\Policy;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Payroll\Models\CompanyProfile;

class CompanyProfileController extends Controller
{
    protected $request;

    /**
     * CompanyProfileController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Policy::canRead(new CompanyProfile());

        $company = CompanyProfile::first();

        if ($this->request->ajax()) {
            return view('ajax.modules.company.profile.index')->withProfile($company);
        }

        return view('modules.company.profile.index')->withProfile($company);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CompanyProfileRequest|Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyProfileRequest $request)
    {
        Policy::canUpdate(new CompanyProfile());
        
        $company = CompanyProfile::first();
        $company->fill($request->except(['logo']));
        $company->logo = $this->getLogo($request, $company);
        $company->save();

        flash('Successfully Updated Profile', 'success');

        return redirect()->to('/');
    }

    private function getLogo(Request $request, $profile)
    {
        $filename = $profile->logo;
        if ($logo = $request->file('logo')) {
            $filename = 'images/' . str_random(10) . Carbon::now()->timestamp . $logo->getClientOriginalExtension();
            $uploaded = Image::make($logo)
                ->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                });
            Image::canvas(310, 310)
                ->insert($uploaded, 'center')
                ->save(public_path($filename));
        }

        return $filename;
    }
}
