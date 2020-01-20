<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Payroll\Models\Allowance;
use Payroll\Models\CompanyProfile;
use Payroll\Models\Deduction;
use Payroll\Models\Payroll;
use Payroll\Models\Policy;
use Payroll\Repositories\PolicyRepository;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        if(env('EXPIRY_DATE') != null)
        {
            $today = Carbon::now()->format('Y-m-d');
            $ex = Carbon::parse(env('EXPIRY_DATE'))->format('Y-m-d');
            if($ex < $today){

                $key = env('LICENSE');
                $client = new \GuzzleHttp\Client();


                $res = $client->request('POST', 'http://license.wizag.biz/api/getlicense', [
                    'form_params' => [
                        'key' => $key,

                    ]
                ]);
                $responseBodyAsString = $res->getBody()->getContents();
                $fin = json_decode($responseBodyAsString, true);

                if($fin['success'] == false) {
                    abort(405);
                }
                if($fin['success'] == true){
                    $today = Carbon::now()->format('Y-m-d');
                    $ex = Carbon::parse($fin['data']['expiry_date'])->format('Y-m-d');

                    if($today < $ex){
                        $this->setEnv('EXPIRY_DATE', $fin['data']['expiry_date']);
                    }else{
                        abort(403);
                    }

                }

            }

        }else{
            $key = env('LICENSE');
            $client = new \GuzzleHttp\Client();


            $res = $client->request('POST', 'http://license.wizag.biz/api/getlicense', [
                'form_params' => [
                    'key' => $key,

                ]
            ]);
            $responseBodyAsString = $res->getBody()->getContents();
            $fin = json_decode($responseBodyAsString, true);

            if($fin['success'] == false) {
                abort(405);
            }
            if($fin['success'] == true){

                $this->setEnv('EXPIRY_DATE', $fin['data']['expiry_date']);


            }

        }


        view()->composer(['layout'], function ($view) {
            $company = CompanyProfile::first();
            $daysPolicy = PolicyRepository::get(Payroll::MODULE_ID, Payroll::ENABLE_DAYS_ATTENDANCE);

            $view->withCompany($company)
                ->withDaysEnabled($daysPolicy);
        });

        Relation::morphMap([
            'Allowance' => Allowance::class,
            'Deduction' => Deduction::class,
        ]);
    }

    public function setEnv($name, $value)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                $name . '=' . env($name), $name . '=' . $value, file_get_contents($path)
            ));
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }
}
