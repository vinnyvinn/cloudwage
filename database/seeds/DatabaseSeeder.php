<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CurrencyTableSeeder::class);
        $this->call(CompanyProfilesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(AllowancesTableSeeder::class);
        $this->call(DeductionsTableSeeder::class);
        $this->call(DeductionSlabsTableSeeder::class);
        $this->call(ReliefsTableSeeder::class);
        $this->call(ModulesTableSeeder::class);
        $this->call(PoliciesTableSeeder::class);
    }
}
