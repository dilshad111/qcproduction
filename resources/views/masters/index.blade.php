@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Masters Management</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <ul class="nav nav-tabs mb-3" id="mastersTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link {{ session('active_tab', 'inks') == 'inks' ? 'active' : '' }}" id="inks-tab" data-bs-toggle="tab" data-bs-target="#inks" type="button"><i class="fas fa-fill-drip"></i> Inks</button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ session('active_tab') == 'carton' ? 'active' : '' }}" id="carton-tab" data-bs-toggle="tab" data-bs-target="#carton" type="button"><i class="fas fa-box-open"></i> Carton Types</button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ session('active_tab') == 'speed' ? 'active' : '' }}" id="speed-tab" data-bs-toggle="tab" data-bs-target="#speed" type="button"><i class="fas fa-gauge-high"></i> Machine Speed</button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ session('active_tab') == 'jobnumber' ? 'active' : '' }}" id="jobnumber-tab" data-bs-toggle="tab" data-bs-target="#jobnumber" type="button"><i class="fas fa-hashtag"></i> Job Card No. Setup</button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ session('active_tab') == 'issuenumber' ? 'active' : '' }}" id="issuenumber-tab" data-bs-toggle="tab" data-bs-target="#issuenumber" type="button"><i class="fas fa-file-invoice"></i> Job Issue No. Setup</button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ session('active_tab') == 'papers' ? 'active' : '' }}" id="papers-tab" data-bs-toggle="tab" data-bs-target="#papers" type="button"><i class="fas fa-scroll"></i> Paper Stocks</button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ session('active_tab') == 'machines' ? 'active' : '' }}" id="machines-tab" data-bs-toggle="tab" data-bs-target="#machines" type="button"><i class="fas fa-cogs"></i> Machines</button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ session('active_tab') == 'staffs' ? 'active' : '' }}" id="staffs-tab" data-bs-toggle="tab" data-bs-target="#staffs" type="button"><i class="fas fa-users"></i> Staff/Operators</button>
        </li>
    </ul>

    <div class="tab-content" id="mastersTabContent">
        <!-- INKS TAB -->
        <div class="tab-pane fade {{ session('active_tab', 'inks') == 'inks' ? 'show active' : '' }}" id="inks">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Add New Ink</div>
                        <div class="card-body">
                            <form action="{{ route('masters.ink.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label>Color Name</label>
                                    <input type="text" name="color_name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Color Code</label>
                                    <input type="text" name="color_code" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary shadow-sm"><i class="fas fa-plus-circle"></i> Add Ink</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered bg-white">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inks as $ink)
                            <tr>
                                <td>{{ $ink->color_name }}</td>
                                <td>{{ $ink->color_code }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning shadow-sm me-1 edit-ink-btn" data-id="{{ $ink->id }}" data-name="{{ $ink->color_name }}" data-code="{{ $ink->color_code }}"><i class="fas fa-edit"></i></button>
                                    <form action="{{ route('masters.ink.destroy', $ink->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Delete?')"><i class="fas fa-trash-can"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- CARTON TYPES TAB -->
        <div class="tab-pane fade {{ session('active_tab') == 'carton' ? 'show active' : '' }}" id="carton">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Add Carton Type</div>
                        <div class="card-body">
                            <form action="{{ route('masters.carton-type.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label>Type Name</label>
                                    <input type="text" name="name" class="form-control" required placeholder="e.g. RSC 201">
                                </div>
                                <div class="mb-3">
                                    <label>Standard Code (FEFCO)</label>
                                    <input type="text" name="standard_code" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary shadow-sm"><i class="fas fa-plus-circle"></i> Add Type</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered bg-white">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>FEFCO Code</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartonTypes as $type)
                            <tr>
                                <td>{{ $type->name }}</td>
                                <td>{{ $type->standard_code }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning shadow-sm me-1 edit-carton-btn" data-id="{{ $type->id }}" data-name="{{ $type->name }}" data-code="{{ $type->standard_code }}"><i class="fas fa-edit"></i></button>
                                    <form action="{{ route('masters.carton-type.destroy', $type->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Delete?')"><i class="fas fa-trash-can"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- MACHINE SPEED TAB -->
        <div class="tab-pane fade {{ session('active_tab') == 'speed' ? 'show active' : '' }}" id="speed">
             <div class="card col-md-6">
                <div class="card-header">Update Machine Speed (m/min)</div>
                <div class="card-body">
                    <form action="{{ route('masters.machine-speed.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>3-Ply Machine Speed</label>
                            <input type="number" name="speed_3ply" class="form-control" value="{{ $machineSpeed->speed_3ply }}">
                        </div>
                        <div class="mb-3">
                            <label>5-Ply Machine Speed</label>
                            <input type="number" name="speed_5ply" class="form-control" value="{{ $machineSpeed->speed_5ply }}">
                        </div>
                        <button type="submit" class="btn btn-primary shadow-sm"><i class="fas fa-save"></i> Update Speeds</button>
                    </form>
                </div>
             </div>
        </div>

        <!-- JOB NUMBER SETUP TAB -->
        <div class="tab-pane fade {{ session('active_tab') == 'jobnumber' ? 'show active' : '' }}" id="jobnumber">
             <div class="card col-md-6">
                <div class="card-header">Job Number Setup</div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    
                    <form action="{{ route('masters.job-number-setup.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Job Card No. Format</label>
                            <input type="text" name="job_number" class="form-control" 
                                   placeholder="e.g., JC-QC-00001" 
                                   value="{{ $jobNumberSetup->prefix . str_pad($jobNumberSetup->current_number + 1, $jobNumberSetup->padding, '0', STR_PAD_LEFT) }}" 
                                   required>
                            <small class="form-text text-muted">
                                Enter the starting job card number. The system will auto-increment from this number.<br>
                                Example: JC-QC-00001 will generate JC-QC-00001, JC-QC-00002, etc.
                            </small>
                        </div>
                        <div class="mb-3">
                            <label>Current Configuration</label>
                            <div class="alert alert-info">
                                <strong>Prefix:</strong> {{ $jobNumberSetup->prefix }}<br>
                                <strong>Next Number:</strong> {{ $jobNumberSetup->prefix . str_pad($jobNumberSetup->current_number + 1, $jobNumberSetup->padding, '0', STR_PAD_LEFT) }}<br>
                                <strong>Padding:</strong> {{ $jobNumberSetup->padding }} digits
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary shadow-sm"><i class="fas fa-save"></i> Update Setup</button>
                    </form>
                </div>
             </div>
        </div>
        
        <!-- JOB ISSUE NUMBER SETUP TAB -->
        <div class="tab-pane fade {{ session('active_tab') == 'issuenumber' ? 'show active' : '' }}" id="issuenumber">
             <div class="card col-md-6">
                <div class="card-header">Job Issue Number Setup</div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    
                    <form action="{{ route('masters.job-issue-number-setup.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Job Issue No. Format</label>
                            <input type="text" name="issue_number" class="form-control" 
                                   placeholder="e.g., JI-00001" 
                                   value="{{ $jobIssueNumberSetup->prefix . str_pad($jobIssueNumberSetup->current_number + 1, $jobIssueNumberSetup->padding, '0', STR_PAD_LEFT) }}" 
                                   required>
                            <small class="form-text text-muted">
                                Enter the starting job issue number. The system will auto-increment from this number.<br>
                                Example: JI-00001 will generate JI-00001, JI-00002, etc.
                            </small>
                        </div>
                        <div class="mb-3">
                            <label>Current Configuration</label>
                            <div class="alert alert-info">
                                <strong>Prefix:</strong> {{ $jobIssueNumberSetup->prefix }}<br>
                                <strong>Next Number:</strong> {{ $jobIssueNumberSetup->prefix . str_pad($jobIssueNumberSetup->current_number + 1, $jobIssueNumberSetup->padding, '0', STR_PAD_LEFT) }}<br>
                                <strong>Padding:</strong> {{ $jobIssueNumberSetup->padding }} digits
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary shadow-sm"><i class="fas fa-save"></i> Update Setup</button>
                    </form>
                </div>
             </div>
        </div>
        
        <!-- PAPERS TAB -->
        <div class="tab-pane fade {{ session('active_tab') == 'papers' ? 'show active' : '' }}" id="papers">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Add New Paper</div>
                        <div class="card-body">
                            <form action="{{ route('masters.paper.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label>Paper Name</label>
                                    <input type="text" name="name" class="form-control" required placeholder="e.g. Kraft">
                                </div>
                                <div class="mb-3">
                                    <label>GSM</label>
                                    <input type="number" name="gsm" class="form-control" required placeholder="e.g. 150">
                                </div>
                                <button type="submit" class="btn btn-primary shadow-sm"><i class="fas fa-plus-circle"></i> Add Paper</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered bg-white">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>GSM</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($papers as $paper)
                            <tr>
                                <td>{{ $paper->name }}</td>
                                <td>{{ $paper->gsm }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning shadow-sm me-1 edit-paper-btn" data-id="{{ $paper->id }}" data-name="{{ $paper->name }}" data-gsm="{{ $paper->gsm }}"><i class="fas fa-edit"></i></button>
                                    <form action="{{ route('masters.paper.destroy', $paper->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Delete?')"><i class="fas fa-trash-can"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- MACHINES TAB -->
        <div class="tab-pane fade {{ session('active_tab') == 'machines' ? 'show active' : '' }}" id="machines">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Add New Machine</div>
                        <div class="card-body">
                            <form action="{{ route('masters.machine.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label>Machine Name</label>
                                    <input type="text" name="name" class="form-control" required placeholder="e.g. Printer A">
                                </div>
                                <div class="mb-3">
                                    <label>Type</label>
                                    <select name="type" class="form-control">
                                        <option value="Printer">Printer</option>
                                        <option value="Die Cutter">Die Cutter</option>
                                        <option value="Rotary Slotter">Rotary Slotter</option>
                                        <option value="Gluer">Gluer</option>
                                        <option value="Stapler">Stapler</option>
                                        <option value="Corrugator">Corrugator</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Department</label>
                                    <select name="department" class="form-control" required>
                                        <option value="Corrugation Plant">Corrugation Plant</option>
                                        <option value="Production Department" selected>Production Department</option>
                                        <option value="Quality Control">Quality Control</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary shadow-sm"><i class="fas fa-plus-circle"></i> Add Machine</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered bg-white">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($machines as $machine)
                            <tr>
                                <td>{{ $machine->name }}</td>
                                <td>{{ $machine->type }}</td>
                                <td>{{ $machine->department ?? 'Production Department' }}</td>
                                <td>{{ $machine->status ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning shadow-sm me-1 edit-machine-btn" data-id="{{ $machine->id }}" data-name="{{ $machine->name }}" data-type="{{ $machine->type }}" data-department="{{ $machine->department ?? 'Production Department' }}"><i class="fas fa-edit"></i></button>
                                    <form action="{{ route('masters.machine.destroy', $machine->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Delete?')"><i class="fas fa-trash-can"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- STAFF TAB -->
        <div class="tab-pane fade {{ session('active_tab') == 'staffs' ? 'show active' : '' }}" id="staffs">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Add New Staff</div>
                        <div class="card-body">
                            <form action="{{ route('masters.staff.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" required placeholder="e.g. John Doe">
                                </div>
                                <div class="mb-3">
                                    <label>Department</label>
                                    <select name="department" id="add_staff_department" class="form-control" required>
                                        <option value="Corrugation Plant">Corrugation Plant</option>
                                        <option value="Production Department" selected>Production Department</option>
                                        <option value="Quality Control">Quality Control</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Role</label>
                                    <select name="role" id="add_staff_role" class="form-control" required>
                                        <option value="Machine Operator">Machine Operator</option>
                                        <option value="Helper">Helper</option>
                                        <option value="Supervisor">Supervisor</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary shadow-sm"><i class="fas fa-plus-circle"></i> Add Staff</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered bg-white">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staffs as $staff)
                            <tr>
                                <td>{{ $staff->name }}</td>
                                <td>{{ $staff->department ?? 'Production Depart' }}</td>
                                <td>{{ $staff->role }}</td>
                                <td>{{ $staff->status ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning shadow-sm me-1 edit-staff-btn" data-id="{{ $staff->id }}" data-name="{{ $staff->name }}" data-department="{{ $staff->department ?? 'Production Depart' }}" data-role="{{ $staff->role }}"><i class="fas fa-edit"></i></button>
                                    <form action="{{ route('masters.staff.destroy', $staff->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Delete?')"><i class="fas fa-trash-can"></i></button>
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
</div>
<!-- Edit Ink Modal -->
<div class="modal fade" id="editInkModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Ink</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editInkForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Color Name</label>
                        <input type="text" name="color_name" id="edit_ink_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Color Code</label>
                        <input type="text" name="color_code" id="edit_ink_code" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Ink</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Carton Modal -->
<div class="modal fade" id="editCartonModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Carton Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCartonForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Type Name</label>
                        <input type="text" name="name" id="edit_carton_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Standard Code (FEFCO)</label>
                        <input type="text" name="standard_code" id="edit_carton_code" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Carton Type</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Paper Modal -->
<div class="modal fade" id="editPaperModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Paper</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editPaperForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Paper Name</label>
                        <input type="text" name="name" id="edit_paper_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>GSM</label>
                        <input type="number" name="gsm" id="edit_paper_gsm" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Paper</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Machine Modal -->
<div class="modal fade" id="editMachineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Machine</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editMachineForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Machine Name</label>
                        <input type="text" name="name" id="edit_machine_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Type</label>
                        <select name="type" id="edit_machine_type" class="form-control">
                            <option value="Printer">Printer</option>
                            <option value="Die Cutter">Die Cutter</option>
                            <option value="Rotary Slotter">Rotary Slotter</option>
                            <option value="Gluer">Gluer</option>
                            <option value="Stapler">Stapler</option>
                            <option value="Corrugator">Corrugator</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Department</label>
                        <select name="department" id="edit_machine_department" class="form-control" required>
                            <option value="Corrugation Plant">Corrugation Plant</option>
                            <option value="Production Department">Production Department</option>
                            <option value="Quality Control">Quality Control</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Machine</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Staff Modal -->
<div class="modal fade" id="editStaffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Staff</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editStaffForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" id="edit_staff_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Department</label>
                        <select name="department" id="edit_staff_department" class="form-control" required>
                            <option value="Corrugation Plant">Corrugation Plant</option>
                            <option value="Production Department">Production Department</option>
                            <option value="Quality Control">Quality Control</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role" id="edit_staff_role" class="form-control">
                            <option value="Machine Operator">Machine Operator</option>
                            <option value="Helper">Helper</option>
                            <option value="Supervisor">Supervisor</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Staff</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@stack('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Edit Ink
        const editInkBtns = document.querySelectorAll('.edit-ink-btn');
        const editInkModal = new bootstrap.Modal(document.getElementById('editInkModal'));
        editInkBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const code = this.getAttribute('data-code');
                
                document.getElementById('editInkForm').action = '/masters/ink/' + id;
                document.getElementById('edit_ink_name').value = name;
                document.getElementById('edit_ink_code').value = code;
                
                editInkModal.show();
            });
        });

        // Edit Carton
        const editCartonBtns = document.querySelectorAll('.edit-carton-btn');
        const editCartonModal = new bootstrap.Modal(document.getElementById('editCartonModal'));
        editCartonBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const code = this.getAttribute('data-code');
                
                document.getElementById('editCartonForm').action = '/masters/carton-type/' + id;
                document.getElementById('edit_carton_name').value = name;
                document.getElementById('edit_carton_code').value = code;
                
                editCartonModal.show();
            });
        });

        // Edit Paper
        const editPaperBtns = document.querySelectorAll('.edit-paper-btn');
        const editPaperModal = new bootstrap.Modal(document.getElementById('editPaperModal'));
        editPaperBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const gsm = this.getAttribute('data-gsm');
                
                document.getElementById('editPaperForm').action = '/masters/paper/' + id;
                document.getElementById('edit_paper_name').value = name;
                document.getElementById('edit_paper_gsm').value = gsm;
                
                editPaperModal.show();
            });
        });

        // Edit Machine
        const editMachineBtns = document.querySelectorAll('.edit-machine-btn');
        const editMachineModal = new bootstrap.Modal(document.getElementById('editMachineModal'));
        editMachineBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const type = this.getAttribute('data-type');
                const department = this.getAttribute('data-department');
                
                document.getElementById('editMachineForm').action = '/masters/machine/' + id;
                document.getElementById('edit_machine_name').value = name;
                document.getElementById('edit_machine_type').value = type;
                document.getElementById('edit_machine_department').value = department;
                
                editMachineModal.show();
            });
        });

        // Edit Staff
        const editStaffBtns = document.querySelectorAll('.edit-staff-btn');
        const editStaffModal = new bootstrap.Modal(document.getElementById('editStaffModal'));
        editStaffBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const department = this.getAttribute('data-department');
                const role = this.getAttribute('data-role');
                
                document.getElementById('editStaffForm').action = '/masters/staff/' + id;
                document.getElementById('edit_staff_name').value = name;
                document.getElementById('edit_staff_department').value = department;
                document.getElementById('edit_staff_role').value = role;
                
                editStaffModal.show();
            });
        });

        // Dynamic Role Options based on Department
        function updateRoleOptions(departmentSelect, roleSelect) {
            const department = departmentSelect.value;
            const currentRole = roleSelect.value;
            
            // Clear existing options
            roleSelect.innerHTML = '';
            
            if (department === 'Quality Control') {
                // QC Department roles
                roleSelect.innerHTML = `
                    <option value="Assistant QA">Assistant QA</option>
                    <option value="QA Manager">QA Manager</option>
                `;
            } else {
                // Production and Corrugation roles
                roleSelect.innerHTML = `
                    <option value="Machine Operator">Machine Operator</option>
                    <option value="Helper">Helper</option>
                    <option value="Supervisor">Supervisor</option>
                `;
            }
            
            // Try to restore previous selection if it exists in new options
            const options = Array.from(roleSelect.options);
            const matchingOption = options.find(opt => opt.value === currentRole);
            if (matchingOption) {
                roleSelect.value = currentRole;
            }
        }
        
        // Add Staff form - department change handler
        const addDeptSelect = document.getElementById('add_staff_department');
        const addRoleSelect = document.getElementById('add_staff_role');
        if (addDeptSelect && addRoleSelect) {
            addDeptSelect.addEventListener('change', function() {
                updateRoleOptions(this, addRoleSelect);
            });
        }
        
        // Edit Staff form - department change handler
        const editDeptSelect = document.getElementById('edit_staff_department');
        const editRoleSelect = document.getElementById('edit_staff_role');
        if (editDeptSelect && editRoleSelect) {
            editDeptSelect.addEventListener('change', function() {
                updateRoleOptions(this, editRoleSelect);
            });
        }
    });
</script>
