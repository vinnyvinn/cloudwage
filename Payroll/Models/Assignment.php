<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['employee_id', 'department_id'];

    const PERMISSIONS = [
        'Create'    => 'assignment.create',
        'Read'      => 'assignment.read',
        'Update'    => 'assignment.update',
        'Delete'    => 'assignment.delete'
    ];

    const MODULE_ID = 4;

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
