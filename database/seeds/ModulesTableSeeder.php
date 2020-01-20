<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Payroll\Models\Advance;
use Payroll\Models\AdvancePayments;
use Payroll\Models\Allowance;
use Payroll\Models\Assignment;
use Payroll\Models\Attendance;
use Payroll\Models\CompanyProfile;
use Payroll\Models\Currency;
use Payroll\Models\Deduction;
use Payroll\Models\DeductionPayments;
use Payroll\Models\DeductionSlab;
use Payroll\Models\Department;
use Payroll\Models\Employee;
use Payroll\Models\EmployeeAllowance;
use Payroll\Models\EmployeeContract;
use Payroll\Models\EmployeeDeduction;
use Payroll\Models\EmployeePaymentMethods;
use Payroll\Models\EmployeeRelief;
use Payroll\Models\EmployeeType;
use Payroll\Models\EmployeeWorkPlanAssignment;
use Payroll\Models\Holiday;
use Payroll\Models\LoanPayments;
use Payroll\Models\Loans;
use Payroll\Models\Module;
use Payroll\Models\Notification;
use Payroll\Models\NotificationType;
use Payroll\Models\PayGrade;
use Payroll\Models\PaymentMethod;
use Payroll\Models\PaymentStructure;
use Payroll\Models\Payroll;
use Payroll\Models\Policy;
use Payroll\Models\Relief;
use Payroll\Models\ReportTemplates;
use Payroll\Models\Setting;
use Payroll\Models\Shift;
use Payroll\Models\Template;
use Payroll\Models\UDF;
use Payroll\Models\WorkPlan;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('modules')->insert(
            [
                [
                    'id' => Advance::MODULE_ID,
                    'name' => 'Advances',
                    'description' => 'This module deals with advance salary to the employees',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => AdvancePayments::MODULE_ID,
                    'name' => 'Advance Payments',
                    'description' => 'This module deals with advance payments made by the employees',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => Allowance::MODULE_ID,
                    'name' => 'Allowances',
                    'description' => 'This module deals with allowances given to the employees',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => Assignment::MODULE_ID,
                    'name' => 'Employee Assignment',
                    'description' => 'This module deals with the assigning of an employee to a department',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => Attendance::MODULE_ID,
                    'name' => 'Attendance',
                    'description' => 'This module deals with the attendance of employees',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => CompanyProfile::MODULE_ID,
                    'name' => 'Company Profile',
                    'description' => 'This module deals with the profile of the organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => Currency::MODULE_ID,
                    'name' => 'Currencies',
                    'description' => 'This module deals with the profile of the organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => Deduction::MODULE_ID,
                    'name' => 'Deductions',
                    'description' => 'This module deals with the deductions to be used by organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => DeductionPayments::MODULE_ID,
                    'name' => 'Deduction Payments',
                    'description' => 'This module deals with the deduction payments made by the employees',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => DeductionSlab::MODULE_ID,
                    'name' => 'Deduction Slabs',
                    'description' => 'This module deals slabs that are attached to the deductions',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => Department::MODULE_ID,
                    'name' => 'Departments',
                    'description' => 'This module deals with the departments present in the organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => Employee::MODULE_ID,
                    'name' => 'Employees',
                    'description' => 'This module deals with the employees in the organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => EmployeeAllowance::MODULE_ID,
                    'name' => 'Employee Allowances',
                    'description' => 'This module deals with the allowances given to employees in the organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => EmployeeContract::MODULE_ID,
                    'name' => 'Employee Contracts',
                    'description' => 'This module deals with the contracts of the employees in the organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => EmployeeDeduction::MODULE_ID,
                    'name' => 'Employee Deductions',
                    'description' => 'This module deals with the deductions assigned to employees in the organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => EmployeePaymentMethods::MODULE_ID,
                    'name' => 'Employee Payment Methods',
                    'description' => 'This module deals with the payment methods used by employees in the organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => EmployeeRelief::MODULE_ID,
                    'name' => 'Employee Reliefs',
                    'description' => 'This module deals with the reliefs given to the employees in the organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => EmployeeType::MODULE_ID,
                    'name' => 'Employee Types',
                    'description' => 'This module deals with the departments present in the organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => EmployeeWorkPlanAssignment::MODULE_ID,
                    'name' => 'Employee Work Plan Assignment',
                    'description' => 'This module deals with assignment of work plans to employees in the organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => Holiday::MODULE_ID,
                    'name' => 'Holidays',
                    'description' => 'This module deals with the holidays considered in the organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => LoanPayments::MODULE_ID,
                    'name' => 'Loan Payments',
                    'description' => 'This module deals with the payments against loans in the organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => Loans::MODULE_ID,
                    'name' => 'Loans',
                    'description' => 'This module deals with loans given to employees in the organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => Module::MODULE_ID,
                    'name' => 'Modules',
                    'description' => 'This module deals with the modules present in the system',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => Notification::MODULE_ID,
                    'name' => 'Notifications',
                    'description' => 'This module deals with the notifications sent to the users of the system',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => NotificationType::MODULE_ID,
                    'name' => 'Notification Types',
                    'description' => 'This module deals with the types of notifications present in the system',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => PayGrade::MODULE_ID,
                    'name' => 'Pay Grades',
                    'description' => 'This module deals with the pay grades used in the organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => PaymentMethod::MODULE_ID,
                    'name' => 'Payment Methods',
                    'description' => 'This module deals with the payment methods used in the organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => PaymentStructure::MODULE_ID,
                    'name' => 'Payment Structures',
                    'description' => 'This module deals with the structures used to pay employees in the organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => Payroll::MODULE_ID,
                    'name' => 'Payroll',
                    'description' => 'This module deals with the generation of payslips',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => Policy::MODULE_ID,
                    'name' => 'Policies',
                    'description' => 'This module deals with the policies governing the system',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => Relief::MODULE_ID,
                    'name' => 'Relief',
                    'description' => 'This module deals with the reliefs used in the organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => ReportTemplates::MODULE_ID,
                    'name' => 'Report Templates',
                    'description' => 'This module deals with the templates used to generate reports',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => Setting::MODULE_ID,
                    'name' => 'Settings',
                    'description' => 'This module deals with the settings of the system',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => Shift::MODULE_ID,
                    'name' => 'Shifts',
                    'description' => 'This module deals with the shifts present in the organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => Template::MODULE_ID,
                    'name' => 'Template',
                    'description' => 'This module deals with the templates used in the system',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => UDF::MODULE_ID,
                    'name' => 'UDFs',
                    'description' => 'This module deals with the user defined fields present in the system',
                    'created_at' => $now,
                    'updated_at' => $now
                ],
                [
                    'id' => WorkPlan::MODULE_ID,
                    'name' => 'Work Plans',
                    'description' => 'This module deals with the work plans present in the organization',
                    'created_at' => $now,
                    'updated_at' => $now
                ]
            ]
        );
    }
}
