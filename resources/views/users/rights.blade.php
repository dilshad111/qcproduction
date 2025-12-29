@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Manage Rights: {{ $user->name }}</h4>
                    <small class="text-muted">{{ $user->email }}</small>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('users.rights.update', $user) }}">
                        @csrf
                        
                        <div class="row">
                            <!-- Main Menu Access -->
                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="mdi mdi-menu"></i> Menu Access</h5>
                                <div class="mb-4" style="padding-left: 10px;">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="menu_access[]" value="dashboard" id="menu_dashboard" {{ $user->canViewMenu('dashboard') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="menu_dashboard">
                                            <i class="mdi mdi-grid-large text-primary"></i> Dashboard
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="menu_access[]" value="customers" id="menu_customers" {{ $user->canViewMenu('customers') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="menu_customers">
                                            <i class="mdi mdi-account-group text-info"></i> Customers
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="menu_access[]" value="job_cards" id="menu_job_cards" {{ $user->canViewMenu('job_cards') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="menu_job_cards">
                                            <i class="mdi mdi-card-text-outline text-success"></i> Job Cards
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="menu_access[]" value="issue_job_order" id="menu_issue_job" {{ $user->canViewMenu('issue_job_order') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="menu_issue_job">
                                            <i class="mdi mdi-file-document-edit text-warning"></i> Issue Job Order
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="menu_access[]" value="corrugation" id="menu_corrugation" {{ $user->canViewMenu('corrugation') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="menu_corrugation">
                                            <i class="mdi mdi-cogs text-dark"></i> Corrugation Plant
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="menu_access[]" value="production" id="menu_production" {{ $user->canViewMenu('production') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="menu_production">
                                            <i class="mdi mdi-factory text-danger"></i> Production
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="menu_access[]" value="masters" id="menu_masters" {{ $user->canViewMenu('masters') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="menu_masters">
                                            <i class="mdi mdi-database text-secondary"></i> Masters
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="menu_access[]" value="admin" id="menu_admin" {{ $user->canViewMenu('admin') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="menu_admin">
                                            <i class="mdi mdi-shield-account text-primary"></i> Admin
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Submenu Access -->
                            <div class="col-md-6">
                                <h5 class="mb-3"><i class="mdi mdi-menu-open"></i> Submenu Access</h5>
                                
                                <div class="mb-3" style="padding-left: 10px;">
                                    <h6 class="text-muted mb-2">Masters Submenus</h6>
                                    <div class="form-check mb-2 ms-3">
                                        <input class="form-check-input" type="checkbox" name="submenu_access[]" value="inks" id="sub_inks" {{ $user->canViewSubmenu('inks') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sub_inks">Inks</label>
                                    </div>
                                    <div class="form-check mb-2 ms-3">
                                        <input class="form-check-input" type="checkbox" name="submenu_access[]" value="papers" id="sub_papers" {{ $user->canViewSubmenu('papers') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sub_papers">Papers</label>
                                    </div>
                                    <div class="form-check mb-2 ms-3">
                                        <input class="form-check-input" type="checkbox" name="submenu_access[]" value="carton_types" id="sub_carton_types" {{ $user->canViewSubmenu('carton_types') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sub_carton_types">Carton Types</label>
                                    </div>
                                    <div class="form-check mb-2 ms-3">
                                        <input class="form-check-input" type="checkbox" name="submenu_access[]" value="suppliers" id="sub_suppliers" {{ $user->canViewSubmenu('suppliers') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sub_suppliers">Suppliers</label>
                                    </div>
                                </div>

                                <div class="mb-3" style="padding-left: 10px;">
                                    <h6 class="text-muted mb-2">Production Submenus</h6>
                                    <div class="form-check mb-2 ms-3">
                                        <input class="form-check-input" type="checkbox" name="submenu_access[]" value="reels" id="sub_reels" {{ $user->canViewSubmenu('reels') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sub_reels">Reel Consumption</label>
                                    </div>
                                    <div class="form-check mb-2 ms-3">
                                        <input class="form-check-input" type="checkbox" name="submenu_access[]" value="dispatch" id="sub_dispatch" {{ $user->canViewSubmenu('dispatch') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sub_dispatch">Dispatch</label>
                                    </div>
                                </div>

                                <div class="mb-3" style="padding-left: 10px;">
                                    <h6 class="text-muted mb-2">Admin Submenus</h6>
                                    <div class="form-check mb-2 ms-3">
                                        <input class="form-check-input" type="checkbox" name="submenu_access[]" value="users" id="sub_users" {{ $user->canViewSubmenu('users') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sub_users">User Management</label>
                                    </div>
                                    <div class="form-check mb-2 ms-3">
                                        <input class="form-check-input" type="checkbox" name="submenu_access[]" value="company" id="sub_company" {{ $user->canViewSubmenu('company') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sub_company">Company Setup</label>
                                    </div>
                                    <div class="form-check mb-2 ms-3">
                                        <input class="form-check-input" type="checkbox" name="submenu_access[]" value="audit_logs" id="sub_audit_logs" {{ $user->canViewSubmenu('audit_logs') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sub_audit_logs">Audit Logs</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Actions -->
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="mb-3"><i class="mdi mdi-shield-check"></i> Permissions</h5>
                                <div class="mb-3" style="padding-left: 10px;">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="can_edit" id="can_edit" {{ $user->hasPermission('can_edit') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="can_edit">
                                            <i class="mdi mdi-pencil text-warning"></i> Can Edit Records
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="can_delete" id="can_delete" {{ $user->hasPermission('can_delete') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="can_delete">
                                            <i class="mdi mdi-delete text-danger"></i> Can Delete Records
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="view_amounts" id="view_amounts" {{ $user->hasPermission('view_amounts') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="view_amounts">
                                            <i class="mdi mdi-currency-usd text-success"></i> View Amounts
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="mdi mdi-content-save"></i> Update Rights
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-lg">
                                <i class="mdi mdi-arrow-left"></i> Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
