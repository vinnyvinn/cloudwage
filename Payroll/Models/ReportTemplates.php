<?php
namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class ReportTemplates extends Model
{
    const MODULE_ID = 32;

    protected $fillable = ['module_id', 'field_order', 'template_name'];
}
