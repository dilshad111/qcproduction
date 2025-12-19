@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Masters Management</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <ul class="nav nav-tabs mb-3" id="mastersTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="inks-tab" data-bs-toggle="tab" data-bs-target="#inks" type="button"><i class="fas fa-fill-drip"></i> Inks</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="carton-tab" data-bs-toggle="tab" data-bs-target="#carton" type="button"><i class="fas fa-box-open"></i> Carton Types</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="speed-tab" data-bs-toggle="tab" data-bs-target="#speed" type="button"><i class="fas fa-gauge-high"></i> Machine Speed</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="jobnumber-tab" data-bs-toggle="tab" data-bs-target="#jobnumber" type="button"><i class="fas fa-hashtag"></i> Job Number Setup</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="papers-tab" data-bs-toggle="tab" data-bs-target="#papers" type="button"><i class="fas fa-scroll"></i> Paper Stocks</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="machines-tab" data-bs-toggle="tab" data-bs-target="#machines" type="button"><i class="fas fa-cogs"></i> Machines</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="staffs-tab" data-bs-toggle="tab" data-bs-target="#staffs" type="button"><i class="fas fa-users"></i> Staff/Operators</button>
        </li>
    </ul>

    <div class="tab-content" id="mastersTabContent">
        <!-- INKS TAB -->
        <div class="tab-pane fade show active" id="inks">
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
        <div class="tab-pane fade" id="carton">
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
        <div class="tab-pane fade" id="speed">
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
        <div class="tab-pane fade" id="jobnumber">
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
        
        <!-- PAPERS TAB -->
        <div class="tab-pane fade" id="papers">
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
        <div class="tab-pane fade" id="machines">
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
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($machines as $machine)
                            <tr>
                                <td>{{ $machine->name }}</td>
                                <td>{{ $machine->type }}</td>
                                <td>{{ $machine->status ? 'Active' : 'Inactive' }}</td>
                                <td>
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
        <div class="tab-pane fade" id="staffs">
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
                                    <label>Role</label>
                                    <select name="role" class="form-control">
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
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staffs as $staff)
                            <tr>
                                <td>{{ $staff->name }}</td>
                                <td>{{ $staff->role }}</td>
                                <td>{{ $staff->status ? 'Active' : 'Inactive' }}</td>
                                <td>
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
@endsection
