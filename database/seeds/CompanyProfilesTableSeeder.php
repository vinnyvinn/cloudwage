<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CompanyProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('company_profiles')->insert([
            'logo' => 'images/smalllogo.jpg',
            'name' => 'Wise & Agile Solutions',
            'city' => 'Nairobi',
            'country' => 'Kenya',
            'phone' => '020-5252453',
            'mobile' => '0711408108 / 0732456456',
            'email' => 'info@wizag.biz',
            'website' => 'http://wizag.biz',
            'kra_pin' => ' ',
            'currency_id' => '75',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
