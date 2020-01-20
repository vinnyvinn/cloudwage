<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeLeave extends Model
{
    const MODULE_ID = 28;
    const REMAINING_LEAVE_DAYS = 'REMAINING LEAVE DAYS';
    const PENDING = 'pending approval';
    const APPROVED = 'approved';
    const DECLINED = 'declined';
    const PERMISSIONS = [
        'Create'    => 'leaves.create',
        'Read'      => 'leaves.read',
        'Update'    => 'leaves.update',
        'Delete'    => 'leaves.delete'
    ];

    protected $fillable = ['employee_id', 'leave_id', 'start_date', 'end_date', 'days', 'status'];

    public function leave()
    {
        return $this->belongsTo(Leave::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
