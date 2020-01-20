<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;
use Payroll\Repositories\PolicyRepository;

class Policy extends Model
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::updated(function () {
            PolicyRepository::reCache();
        });
    }

    const MODULE_ID = 30;

    protected $fillable = ['module_id', 'policy', 'value', 'exceptions'];

    const PERMISSIONS = [
        'Create'    => 'policy.create',
        'Read'      => 'policy.read',
        'Update'    => 'policy.update',
        'Delete'    => 'policy.delete'
    ];

    public function scopeEnabled($query)
    {
        return $query->whereEnabled(true);
    }

    public function scopeDisabled($query)
    {
        return $query->whereEnabled(false);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
