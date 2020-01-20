<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class UDF extends Model
{
    const MODULE_ID = 36;

    protected $table = 'udfs';

    protected $fillable = ['id','udfable_id', 'udfable_type', 'field_title', 'field_name', 'created_at', 'updated_at'];

    public function udfable()
    {
        return $this->morphTo();
    }
}
