<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Schema;

class EmployeePaymentMethods extends Model
{
    protected $fillable = ['employee_id', 'payment_method_id', 'bank-name', 'bank-branch', 'account-number'];

    const MODULE_ID = 16;

    public function clearAttributes()
    {
        collect($this->attributes)->reject(function ($value, $key) {
            return $key == 'id' || $key == 'employee_id' ||
                    $key == 'payment_method_id' || $key == 'created_at' ||
                    $key == 'updated_at';
        })->each(function ($value, $key) {
            $this->$key = null;
        });

        return $this;
    }

    public function updateWithUdfs(Collection $collection, $data)
    {
        $collection->each(function ($value) use ($data) {
            $field = $value->field_name;
            $this->$field = $data[$field];
        });

        return $this;
    }
    
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public static function dropColumns($columns = array())
    {
        Schema::table('employee_payment_methods', function ($table) use ($columns) {
            $table->dropColumn($columns);
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
