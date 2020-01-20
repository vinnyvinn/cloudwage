<?php

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['branch_id', 'parent_id', 'name'];

    const PERMISSIONS = [
        'Create'    => 'department.create',
        'Read'      => 'department.read',
        'Update'    => 'department.update',
        'Delete'    => 'department.delete'
    ];


    const MODULE_ID = 11;
    
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branches::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'assignments');
    }

}
