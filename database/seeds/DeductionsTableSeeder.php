<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DeductionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('deductions')->insert([
            [
                'id' => 1,
                'name' => 'PAYE',
                'due_date' => 9,
                'threshold' => 11135,
                'type' => 'slab',
                'rate' => null,
                'has_relief' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 2,
                'name' => 'NHIF',
                'due_date' => 9,
                'threshold' => 0,
                'type' => 'slab',
                'rate' => null,
                'has_relief' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 3,
                'name' => 'NSSF',
                'due_date' => 15,
                'threshold' => 0,
                'type' => 'slab',
                'rate' => null,
                'has_relief' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);
    }
}
