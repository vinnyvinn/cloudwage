<?php

namespace App;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Payroll\Models\Employee;

class User extends EloquentUser
{
    const PERMISSIONS = [
        'Create'    => 'user.create',
        'Read'      => 'user.read',
        'Update'    => 'user.update',
        'Delete'    => 'user.delete'
    ];

    protected $fillable = ['username', 'password', 'email'];

    protected $loginNames = ['username'];

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function isArchived()
    {
        return $this->trashed();
    }

    public static function register($credentials = array(), $permissions = array())
    {
        $user = Sentinel::registerAndActivate($credentials);
        $user->permissions = $permissions;
        $user->save();

        return $user;
    }
}
