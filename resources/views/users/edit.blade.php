@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="mdi mdi-account-edit"></i> Edit User</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.update', $user) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label"><i class="mdi mdi-account"></i> Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="mdi mdi-email"></i> Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><i class="mdi mdi-shield-account"></i> Role</label>
                            <select name="role" class="form-select">
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                <option value="operator" {{ $user->role == 'operator' ? 'selected' : '' }}>Operator</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>

                        <hr class="my-4">
                        <h6 class="text-muted mb-3">Change Password (Optional)</h6>

                        <div class="mb-3">
                            <label class="form-label"><i class="mdi mdi-lock"></i> New Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                            <small class="text-muted">Minimum 6 characters</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="mdi mdi-lock-check"></i> Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Update User
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
