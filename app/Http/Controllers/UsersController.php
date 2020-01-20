<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Policies\Policy;
use App\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

use App\Http\Requests;
use Payroll\Handlers\Authentication\Authenticator;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Policy::canRead(new User());
        
        return view('modules.settings.users.index')->withUsers(User::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Policy::canCreate(new User());

        return view('modules.settings.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        Policy::canCreate(new User());
        $permissions = [];

        if ($request->has('permissions')) {
            foreach ($request->get('permissions') as $permission) {
                $permissions[$permission] = true;
            }
        }
        User::register($request->only(['username', 'email', 'password']), $permissions);
        flash('Successfully added new user', 'success');

        return redirect()->route('users.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $userId
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($userid)
    {
        Policy::canUpdate(new User());

        return view('modules.settings.users.edit')->withUser(User::findOrFail($userid));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userid
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $userid)
    {
        Policy::canUpdate(new User());

        $user = User::findOrFail($userid);
        $permissions = [];
        if ($request->has('permissions')) {
            foreach ($request->get('permissions') as $permission) {
                $permissions[$permission] = true;
            }
        }
        $user->fill($request->all());
        $user->permissions = $permissions;
        $user->save();
        flash('Successfully edited user details', 'success');

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $userid
     * @return \Illuminate\Http\Response
     */
    public function destroy($userid)
    {
        Policy::canDelete(new User());

        $user = User::findOrFail($userid);
        $user->delete();
        flash('Successfully deleted user', 'success');

        return redirect()->route('users.index');
    }

    public function profile()
    {
        return view('modules.settings.users.profile')->withUser(Sentinel::getUser());
    }

    public function postProfile(ProfileUpdateRequest $request)
    {
        $user = Sentinel::getUser();
        $credentials = [
            'email'    => $user->email,
            'password' => $request->get('old_password'),
        ];

        if (! Sentinel::validateCredentials($user, $credentials)) {
            flash('Sorry, old password is incorrect', 'error');

            return redirect()->back()->withInput();
        }

        Sentinel::update($user, [
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ]);
        flash('Successfully saved changes. Please sign in to continue.', 'success');

        return Authenticator::logout()->withErrors(['message' => 'Successfully saved changes. Please sign in to continue.']);
    }
}
