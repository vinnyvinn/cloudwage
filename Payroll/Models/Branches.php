<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class Branches extends Model
{
    protected $fillable = ['branch_name', 'location'];

    const PERMISSIONS = [
        'Create'    => 'branch.create',
        'Read'      => 'branch.read',
        'Update'    => 'branch.update',
        'Delete'    => 'branch.delete'
    ];

    public function departments()
    {
        return $this->hasMany(Department::class, 'branch_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'branch_id');
    }
}
