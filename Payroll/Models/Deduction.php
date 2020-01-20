<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    protected $fillable = [
        'name', 'due_date', 'type', 'threshold', 'rate', 'has_relief'
    ];

    const MODULE_ID = 8;

    const PERMISSIONS = [
        'Create'    => 'deduction.create',
        'Read'      => 'deduction.read',
        'Update'    => 'deduction.update',
        'Delete'    => 'deduction.delete'
    ];

    const
        PAYE = 1,
        NHIF = 2,
        NSSF = 3;

    public function relief()
    {
        return $this->morphOne(Relief::class, 'reliefable');
    }

    public function slabs()
    {
        return $this->hasMany(DeductionSlab::class);
    }

    public function employeeDeductions()
    {
        return $this->hasMany(EmployeeDeduction::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_deductions');
    }

    public function payments()
    {
        return $this->hasMany(DeductionPayments::class);
    }

    public function __distruct()
    {
        $this->employees = null;
    }

    public static function create(array $attributes = [])
    {
        $attributes['type'] == 'rate' ? : $attributes['rate'] = null;
        $deduction = parent::create($attributes);
        $attributes['deduction_id'] = $deduction->id;

        if ($deduction->has_relief) {
            $attributes['reliefable_id'] = $deduction->id;
            $attributes['reliefable_type'] = 'Deduction';
            $attributes['name'] = $attributes['relief_name'];
            $attributes['amount'] = $attributes['relief_amount'];

            Relief::create($attributes);
        }

        if ($deduction->type == 'slab') {
            DeductionSlab::bulkCreate($attributes);
        }

        return $deduction;
    }

    /**
     * Update the model in the database.
     *
     * @param  array $attributes
     * @param  array $options
     *
     * @return bool|int
     */
    public function update(array $attributes = [], array $options = [])
    {
        $oldDeduction = $this->replicate();
        $attributes['type'] == 'rate' ? : $attributes['rate'] = null;
        $attributes['reliefable_id'] = $this->id;
        $attributes['deduction_id'] = $this->id;
        $attributes['reliefable_type'] = 'Deduction';

        if ($attributes['type'] == 'rate' && $oldDeduction->type == 'slab') {
            $this->slabs()->delete();
        }

        $this->fill($attributes);
        $this->save();
        $this->manageRelief($oldDeduction, $attributes);

        if ($oldDeduction->type == 'slab') {
            $this->slabs()->delete();
        }

        if ($this->type == 'slab') {
            DeductionSlab::bulkCreate($attributes);
        }

        return true;
    }

    private function manageRelief(Model $oldDeduction, $attributes)
    {
        if ($oldDeduction->has_relief) {
            $this->relief()->delete();
        }

        if ($this->has_relief) {
            $attributes['name'] = $attributes['relief_name'];
            $attributes['amount'] = $attributes['relief_amount'];
            Relief::create($attributes);
        }
    }
}
