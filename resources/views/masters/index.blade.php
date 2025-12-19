@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Masters Management</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <ul class="nav nav-tabs mb-3" id="mastersTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="inks-tab" data-bs-toggle="tab" data-bs-target="#inks" type="button">Inks</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="carton-tab" data-bs-toggle="tab" data-bs-target="#carton" type="button">Carton Types</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="speed-tab" data-bs-toggle="tab" data-bs-target="#speed" type="button">Machine Speed</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="jobnumber-tab" data-bs-toggle="tab" data-bs-target="#jobnumber" type="button">Job Number Setup</button>
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
                                <button type="submit" class="btn btn-primary">Add Ink</button>
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
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
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
                                <button type="submit" class="btn btn-primary">Add Type</button>
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
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
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
                        <button type="submit" class="btn btn-primary">Update Speeds</button>
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
                        <button type="submit" class="btn btn-primary">Update Job Number Setup</button>
                    </form>
                </div>
             </div>
        </div>
    </div>
</div>
@endsection
