<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    protected $fillable = [
        'name', 'non_cash', 'currency_id', 'type', 'rate', 'taxable', 'tax_rate',
        'has_relief', 'in_basic', 'system_install'
    ];

    const MODULE_ID = 3;

    const PERMISSIONS = [
        'Create'    => 'allowance.create',
        'Read'      => 'allowance.read',
        'Update'    => 'allowance.update',
        'Delete'    => 'allowance.delete'
    ];

    /**
     * Save a new model and return the instance.
     *
     * @param  array $attributes
     *
     * @return Model|Allowance
     */
    public static function create(array $attributes = [])
    {
        $allowance = parent::create($attributes);
        $attributes['allowance_id'] = $allowance->id;

        if ($allowance->has_relief) {
            $attributes['reliefable_id'] = $allowance->id;
            $attributes['reliefable_type'] = 'Allowance';
            $attributes['name'] = $attributes['relief_name'];
            $attributes['amount'] = $attributes['relief_amount'];

            Relief::create($attributes);
        }

        return $allowance;
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
        $oldAllowance = $this->replicate();
        $this->fill($attributes);
        $this->save();

        if ($this->has_relief) {
            $attributes['reliefable_id'] = $this->id;
            $attributes['reliefable_type'] = 'Allowance';
            $attributes['name'] = $attributes['relief_name'];
            $attributes['amount'] = $attributes['relief_amount'];
        }

        $this->manageRelief($oldAllowance, $attributes);

        return true;
    }

    private function manageRelief(Model $oldAllowance, $attributes)
    {
        if ($oldAllowance->has_relief) {
            $this->relief()->delete();
        }

        if ($this->has_relief) {
            Relief::create($attributes);
        }
    }

    public function relief()
    {
        return $this->morphOne(Relief::class, 'reliefable');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function employeeAllowances()
    {
        return $this->hasMany(EmployeeAllowance::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_allowances');
    }
}
