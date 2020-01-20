<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Payroll\Models\Employee;

class SpecialAllowance extends Model
{
    //


    protected $fillable = ['employee_id', 'type', 'for_month', 'rate', 'name', 'units', 'total'];
    protected $dates = ['for_month'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
