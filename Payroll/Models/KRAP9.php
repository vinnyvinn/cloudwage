<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class KRAP9 extends Model
{
    protected $table = "krap9";

    protected $fillable = [
        'employee_id', 'for_month', 'basic_salary', 'non_cash',
        'quarters', 'nssf', 'tax_charged', 'relief', 'paye', 'prescribed_rate'
    ];

    const PERMISSIONS = [
        'Create'    => 'kra.create',
        'Read'      => 'kra.read',
        'Update'    => 'kra.update',
        'Delete'    => 'kra.delete'
    ];

    protected $dates = ['for_month'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
