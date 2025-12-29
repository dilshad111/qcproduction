@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2><i class="mdi mdi-account-multiple"></i> Users</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('users.create') }}" class="btn btn-primary shadow-sm">
                <i class="mdi mdi-account-plus"></i> Create User
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th width="280">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <i class="mdi mdi-account-circle text-primary"></i>
                                {{ $user->name }}
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->isAdmin())
                                    <span class="badge bg-danger">Admin</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('users.rights', $user) }}" class="btn btn-sm btn-info text-white" title="Manage Rights">
                                        <i class="mdi mdi-key"></i> Rights
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning text-white" title="Edit User">
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </a>
                                    @if(!$user->isAdmin() || Auth::user()->id !== $user->id)
                                    <button type="button" class="btn btn-sm btn-danger" title="Delete User" 
                                            onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')">
                                        <i class="mdi mdi-delete"></i> Delete
                                    </button>
                                    @endif
                                </div>
                                
                                <!-- Hidden delete form -->
                                <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="mdi mdi-alert"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete user <strong id="userName"></strong>?</p>
                <p class="text-muted mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="mdi mdi-close"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="mdi mdi-delete"></i> Delete User
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let deleteUserId = null;

function confirmDelete(userId, userName) {
    deleteUserId = userId;
    document.getElementById('userName').textContent = userName;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (deleteUserId) {
        document.getElementById('delete-form-' + deleteUserId).submit();
    }
});
</script>
@endsection
