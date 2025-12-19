@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Manage Rights: {{ $user->name }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('users.rights.update', $user) }}">
                        @csrf
                        
                        <h5>Menu Access</h5>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="menu_access[]" value="customers" id="menu_customers" {{ $user->canViewMenu('customers') ? 'checked' : '' }}>
                                <label class="form-check-label" for="menu_customers">Customers</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="menu_access[]" value="masters" id="menu_masters" {{ $user->canViewMenu('masters') ? 'checked' : '' }}>
                                <label class="form-check-label" for="menu_masters">Masters</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="menu_access[]" value="job_cards" id="menu_job_cards" {{ $user->canViewMenu('job_cards') ? 'checked' : '' }}>
                                <label class="form-check-label" for="menu_job_cards">Job Cards</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="menu_access[]" value="production" id="menu_production" {{ $user->canViewMenu('production') ? 'checked' : '' }}>
                                <label class="form-check-label" for="menu_production">Production</label>
                            </div>
                        </div>

                        <hr>
                        <h5>Actions</h5>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="can_edit" id="can_edit" {{ $user->hasPermission('can_edit') ? 'checked' : '' }}>
                                <label class="form-check-label" for="can_edit">Can Edit Records</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="can_delete" id="can_delete" {{ $user->hasPermission('can_delete') ? 'checked' : '' }}>
                                <label class="form-check-label" for="can_delete">Can Delete Records</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="view_amounts" id="view_amounts" {{ $user->hasPermission('view_amounts') ? 'checked' : '' }}>
                                <label class="form-check-label" for="view_amounts">View Amounts</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Rights</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
