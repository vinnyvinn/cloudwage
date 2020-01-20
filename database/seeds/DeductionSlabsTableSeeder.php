<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DeductionSlabsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('deduction_slabs')->insert(
            [
                [
                    'id' => 1,
                    'deduction_id' => 1,
                    'slab_number' => 1,
                    'min_amount' => 0,
                    'max_amount' => 11180,
                    'rate' => '10%',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 2,
                    'deduction_id' => 1,
                    'slab_number' => 2,
                    'min_amount' => 11181,
                    'max_amount' => 21714,
                    'rate' => '15%',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 3,
                    'deduction_id' => 1,
                    'slab_number' => 3,
                    'min_amount' => 21715,
                    'max_amount' => 32248,
                    'rate' => '20%',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 4,
                    'deduction_id' => 1,
                    'slab_number' => 4,
                    'min_amount' => 32249,
                    'max_amount' => 42781,
                    'rate' => '25%',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 5,
                    'deduction_id' => 1,
                    'slab_number' => 5,
                    'min_amount' => 42782,
                    'max_amount' => 0,
                    'rate' => '30%',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 6,
                    'deduction_id' => 2,
                    'slab_number' => 1,
                    'min_amount' => 0,
                    'max_ amount' => 5999,
                    'rate' => '150',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 7,
                    'deduction_id' => 2,
                    'slab_number' => 2,
                    'min_amount' => 6000,
                    'max_ amount' => 7999,
                    'rate' => '300',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 8,
                    'deduction_id' => 2,
                    'slab_number' => 3,
                    'min_amount' => 8000,
                    'max_ amount' => 11999,
                    'rate' => '400',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 9,
                    'deduction_id' => 2,
                    'slab_number' => 4,
                    'min_amount' => 12000,
                    'max_ amount' => 14999,
                    'rate' => '500',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 10,
                    'deduction_id' => 2,
                    'slab_number' => 5,
                    'min_amount' => 15000,
                    'max_ amount' => 19999,
                    'rate' => '600',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 11,
                    'deduction_id' => 2,
                    'slab_number' => 6,
                    'min_amount' => 20000,
                    'max_ amount' => 24999,
                    'rate' => '750',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 12,
                    'deduction_id' => 2,
                    'slab_number' => 7,
                    'min_amount' => 25000,
                    'max_ amount' => 29999,
                    'rate' => '850',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 13,
                    'deduction_id' => 2,
                    'slab_number' => 8,
                    'min_amount' => 30000,
                    'max_ amount' => 34999,
                    'rate' => '900',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 14,
                    'deduction_id' => 2,
                    'slab_number' => 9,
                    'min_amount' => 35000,
                    'max_ amount' => 39999,
                    'rate' => '950',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 15,
                    'deduction_id' => 2,
                    'slab_number' => 10,
                    'min_amount' => 40000,
                    'max_ amount' => 44999,
                    'rate' => '1000',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 16,
                    'deduction_id' => 2,
                    'slab_number' => 11,
                    'min_amount' => 45000,
                    'max_ amount' => 49999,
                    'rate' => '1100',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 17,
                    'deduction_id' => 2,
                    'slab_number' => 12,
                    'min_amount' => 50000,
                    'max_ amount' => 59999,
                    'rate' => '1200',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 18,
                    'deduction_id' => 2,
                    'slab_number' => 13,
                    'min_amount' => 60000,
                    'max_ amount' => 69999,
                    'rate' => '1300',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 19,
                    'deduction_id' => 2,
                    'slab_number' => 14,
                    'min_amount' => 70000,
                    'max_ amount' => 79999,
                    'rate' => '1400',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 20,
                    'deduction_id' => 2,
                    'slab_number' => 15,
                    'min_amount' => 80000,
                    'max_ amount' => 89999,
                    'rate' => '1500',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 21,
                    'deduction_id' => 2,
                    'slab_number' => 16,
                    'min_amount' => 90000,
                    'max_ amount' => 99999,
                    'rate' => '1600',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 22,
                    'deduction_id' => 2,
                    'slab_number' => 17,
                    'min_amount' => 100000,
                    'max_ amount' => 0,
                    'rate' => '1700',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 23,
                    'deduction_id' => 3,
                    'slab_number' => 1,
                    'min_amount' => 0,
                    'max_ amount' => 6000,
                    'rate' => '6',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => 24,
                    'deduction_id' => 3,
                    'slab_number' => 2,
                    'min_amount' => 6001,
                    'max_ amount' => 18000,
                    'rate' => '6',
                    'created_at' => $now,
                    'updated_at' => $now
                ]
            ]
        );
    }
}
