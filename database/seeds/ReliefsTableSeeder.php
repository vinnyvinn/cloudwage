<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReliefsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('reliefs')->insert([
            'id' => 1,
            'name' => 'Personal Relief',
            'reliefable_id' => 1,
            'reliefable_type' => 'Deduction',
            'amount' => 1280,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
