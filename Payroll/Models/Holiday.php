<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['name', 'holiday_day', 'holiday_month'];

    const MODULE_ID = 20;


    const PERMISSIONS = [
        'Create'    => 'holiday.create',
        'Read'      => 'holiday.read',
        'Update'    => 'holiday.update',
        'Delete'    => 'holiday.delete'
    ];
}
