<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class UnitsProduced extends Model
{
    protected $fillable = ['employee_id', 'for_month', 'units_produced'];

    protected $dates = ['for_month'];

    const PERMISSIONS = [
        'Create'    => 'units_produced.create',
        'Read'      => 'units_produced.read',
        'Update'    => 'units_produced.update',
        'Delete'    => 'units_produced.delete'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
