<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Payroll\Models\Employee;

class OT extends Model
{
    //
    protected $table = 'o_ts';
    protected $fillable = ['employee_id', 'payroll_date', 'ot_1_rate', 'ot_1_hrs',
                            'ot_2_rate', 'ot_2_hrs', 'ot_1_amount', 'ot_2_amount', 'amount', 'finalized' ];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
