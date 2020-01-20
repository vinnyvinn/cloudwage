<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Payroll\Models\Advance;
use Payroll\Models\Loans;
use Payroll\Models\Payroll;
use Payroll\Parsers\LoanCalculator;

class PoliciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('policies')->insert(
            [
                [
                    'module_id' => Advance::MODULE_ID,
                    'policy' => Advance::ALLOW_OTHER_MONTHS,
                    'value' => 'false',
                    'postfix' => '',
                    'exceptions' => '',
                    'description' => 'This policy determines whether an advance can be taken on a month other than the current month.',
                    'enabled' => true,
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'module_id' => Advance::MODULE_ID,
                    'policy' => Advance::ALLOW_MULTIPLE_ADVANCES,
                    'value' => 'false',
                    'postfix' => '',
                    'exceptions' => '',
                    'description' => 'This policy determines whether an employee can take an advance when he has another unpaid advance.',
                    'enabled' => true,
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'module_id' => Advance::MODULE_ID,
                    'policy' => Advance::ALLOW_MORE_THAN_BASIC,
                    'value' => 'false',
                    'postfix' => '',
                    'exceptions' => '',
                    'description' => 'This policy determines whether an employee can take an advance that is more than his basic pay.',
                    'enabled' => true,
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'module_id' => Advance::MODULE_ID,
                    'policy' => Advance::GROSS_PAY_PERCENTAGE,
                    'value' => 50,
                    'postfix' => '%',
                    'exceptions' => '',
                    'description' => 'This policy determines the maximum percentage of the gross pay that can be given as an advance.',
                    'enabled' => true,
                    'created_at' => $now,
                    'updated_at' => $now
                ],
//                [
//                    'module_id' => Advance::MODULE_ID,
//                    'policy' => Advance::CHANGE_UNPAID_TO_LOAN,
//                    'value' => 'true',
//                    'postfix' => '',
//                    'exceptions' => '',
//                    'description' => 'This policy determines whether an unpaid advance changes to a loan at end of month.',
//                    'enabled' => true,
//                    'created_at' => $now,
//                    'updated_at' => $now
//                ],
                [
                    'module_id' => Loans::MODULE_ID,
                    'policy' => LoanCalculator::LOAN_PRESCRIBED_RATE,
                    'value' => '15',
                    'postfix' => '%',
                    'exceptions' => '',
                    'description' => 'This policy refers to the prescribed loan rate set by KRA.',
                    'enabled' => true,
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'module_id' => Payroll::MODULE_ID,
                    'policy' => Payroll::ENABLE_DAYS_ATTENDANCE,
                    'value' => 'true',
                    'postfix' => '',
                    'exceptions' => '',
                    'description' => 'This policy determines whether the time attendance Days module is enabled. Disabling it will configure the system to work as if all monthly paid employees were present for all days in a month',
                    'enabled' => true,
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'module_id' => Payroll::MODULE_ID,
                    'policy' => Payroll::NUMBER_OF_DAYS,
                    'value' => '20',
                    'postfix' => ' Days',
                    'exceptions' => '',
                    'description' => 'This policy defines the expected number of working days.',
                    'enabled' => true,
                    'created_at' => $now,
                    'updated_at' => $now
                ]

            ]
        );
    }
}
