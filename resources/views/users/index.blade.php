@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Users</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('users.create') }}" class="btn btn-primary shadow-sm"><i class="fas fa-user-plus"></i> Create User</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>
                    <a href="{{ route('users.rights', $user) }}" class="btn btn-sm btn-info text-white shadow-sm"><i class="fas fa-key"></i> Rights</a>
                    <!-- Add Edit/Delete later if needed -->
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
