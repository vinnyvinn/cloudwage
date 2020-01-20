<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <li class="start active ">
                <a href="{{ url('/') }}">
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="fa fa-archive"></i>
                    <span class="title">Organization</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="{{ route('profile.index') }}" class="ajaxLink">
                            Company Profile </a>
                    </li>
                    <li>
                        <a href="{{ route('departments.index') }}" class="ajaxLink">
                            Branches & Departments
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('allowances.index') }}">
                            Company Allowances
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('deductions.index') }}">
                            Company Deductions
                        </a>
                    </li>

                    {{--<li>--}}
                        {{--<a href="{{ route('reliefs.index') }}">--}}
                            {{--Reliefs--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    <li>
                        <a href="{{ route('policies.index') }}">
                            <span class="badge badge-dager">important</span>
                            System Policies</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="icon-badge"></i>
                    <span class="title">Structures</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="{{ route('shifts.index') }}">
                            Shifts</a>
                    </li>
                    <li>
                        <a href="{{ route('work-plans.index') }}">
                            Work Plans</a>
                    </li>
                    <li>
                        <a href="{{ route('holidays.index') }}">
                            Holidays</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <span class="badge badge-warning">new</span>
                    <i class="icon-pointer"></i>
                    <span class="title">Payments</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="{{ route('payment-methods.index') }}">
                            Payment Methods
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('payment-structures.index') }}">
                            Payment Structures
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('grades.index') }}">
                            Pay Grades</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="icon-rocket"></i>
                    <span class="title">Employees</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="{{ route('employee-types.index') }}">
                            Employee Types</a>
                    </li>
                    <li>
                        <a href="{{ route('employees.index') }}">
                            Employees</a>
                    </li>
                    <li>
                        <a href="{{ route('contracts.index') }}">
                            Employee Contracts</a>
                    </li>
                    <li>
                        <a href="{{ route('assignments.index') }}">
                            Employee Assignments</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="icon-user-following"></i>
                    <span class="title">Advances</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="{{ route('advances.index') }}">
                            Advances</a>
                    </li>
                    <li>
                        <a href="{{ route('loans.index') }}">
                            Loans</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="icon-briefcase"></i>
                    <span class="title">Time Attendance</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub-menu">
                    {{--@if ($days_enabled == 'true')--}}
                    <li>
                        <a href="{{ route('worked.index') }}">
                            Days Worked</a>
                    </li>
                    {{--@endif--}}
                    <li>
                        <a href="{{ route('overtime.index') }}">
                            Overtime Hours</a>
                    </li>
                    {{--<li>--}}
                        {{--<a href="{{ route('days-off.index') }}">--}}
                            {{--Days Off Payments</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="{{ route('holiday-pay.index') }}">--}}
                            {{--Holiday Payments</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="{{ route('leave-pay.index') }}">--}}
                            {{--Leave Payments</a>--}}
                    {{--</li>--}}
                    <li>
                        <a href="{{ route('hours-worked.index') }}">
                            Hours Worked</a>
                    </li>

                    <li>
                        <a href="{{ route('units-made.index') }}">
                            Units Made</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="icon-pointer"></i>
                    <span class="title">Payroll</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="{{ route('payroll.create') }}">
                            Generate Payroll</a>
                    </li>
                    <li>
                        <a href="{{ route('payroll.index') }}">
                            <span class="badge badge-warning">new</span>
                            Generated Payrolls</a>
                    </li>
                    <li>
                        <a href="{{ route('payroll.report') }}">
                            <span class="badge badge-warning">new</span>
                            Generate Report</a>
                    </li>
                    <li>
                        <a href="{{ route('print.payslips') }}">
                            <span class="badge badge-warning">new</span>
                            Print Batch Payslips</a>
                    </li>
                     <li>
                        <a href="{{ route('kra.imports') }}">
                            <span class="badge badge-warning">new</span>
                            Generate KRA Imports</a>
                    </li>
                    <li>
                        <a href="{{ route('coinage.create') }}">
                            <span class="badge badge-warning">new</span>
                            Get Coinage</a>
                    </li>
                </ul>
            </li>
            {{--<li>--}}
                {{--<a href="{{ route('leave.index') }}">--}}
                    {{--<i class="icon-plane"></i>--}}
                    {{--<span class="title">Days Absent</span>--}}
                {{--</a>--}}
            {{--</li>--}}
            <li>
                <a href="javascript:;">
                    <i class="icon-book-open"></i>
                    <span class="title">Reports</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub-menu">
                     <li>
                        <a href="{{route('eft.index')}}">EFT</a>
                    </li>
                    <li>
                        <a href="{{ route('statutory-files') }}">Statutory Files</a>
                    </li>


                    <li>
                        <a href="{{ route('tax.index', ['type' => 'p9']) }}">
                            Tax - P9</a>
                    </li>
                    <li>
                        <a href="{{ route('tax.index', ['type' => 'p10a']) }}">
                            Tax - P10A</a>
                    </li>
                    <li>
                        <a href="{{ route('tax.index', ['type' => 'p10b']) }}">
                            Tax - P10B</a>
                    </li>
                </ul>
            </li>
            <li class="last ">
                <a href="javascript:;">
                    <i class="icon-settings"></i>
                    <span class="title">Settings</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub-menu">

                    <li>
                        <a href="{{ route('users.index') }}">
                            System Users</a>
                    </li>
                    <li>
                        <a href="{{ route('general.index') }}">
                            System Settings</a>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
</div>