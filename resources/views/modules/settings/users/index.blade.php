@extends('layout')

@section('content')
    <div class="page-head">
        <div class="page-title">
            <h1>System Users - <small> Set up the users of the system.</small></h1>
        </div>
    </div>
    <ul class="page-breadcrumb breadcrumb">
        <li>
            <a href="{{ url('/') }}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <a href="{{ route('users.index') }}">System Users</a>
        </li>
    </ul>
    <div class="row">
        <div class="col-sm-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart theme-font-color hide"></i>
                        <span class="caption-subject theme-font-color bold uppercase">Current System Users</span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('users.create') }}" class="btn btn-transparent grey-salsa btn-circle btn-sm active"><i class="fa fa-plus"></i> Create User</a>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-hover table-responsive dataTable">
                        <thead>
                            <tr>
                                <th style="text-align: left">Username</th>
                                <th>Email</th>
                                <th>Last Login</th>
                                <th>Created At</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                    @foreach($users as $user)
                        @if($user->hasAccess(['superuser']))
                            <?php continue; ?>
                        @endif
                            <tr>
                                <th style="text-align: left">{{ $user->username }}</th>
                                <th>{{ $user->email }}</th>
                                <th>{{ \Carbon\Carbon::parse($user->last_login)->format('d F Y') }}</th>
                                <th>{{ $user->created_at->format('d F Y') }}</th>
                                <th>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info btn-xs">Edit</a>
                                </th>
                                <td>
                                    <a href="{{ route('users.destroy', $user->id) }}" class="btn btn-danger btn-xs" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this user?" data-token="{{ csrf_token() }}">Delete</a>
                                </td>
                            </tr>
                    @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

