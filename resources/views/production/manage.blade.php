@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-3">
             <h2>Production Management: {{ $jobIssue->jobCard->job_no }}</h2>
             <span class="badge bg-secondary">PO: {{ $jobIssue->po_number }}</span>
             <span class="badge bg-info">Customer: {{ $jobIssue->customer->name }}</span>
             <span class="badge bg-dark">Order Qty: {{ $jobIssue->order_qty_cartons }}</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <!-- REEL DATA ENTRY -->
        <div class="col-md-6">
            <!-- REEL DATA MOVED TO CORRUGATION -->


            <!-- TIME ESTIMATION -->
            <div class="card mb-3">
                <div class="card-header bg-warning text-dark">Time Estimation</div>
                <div class="card-body">
                    <h5>Estimated Job Duration: <strong>{{ $estimatedTime }}</strong> (HH:MM:SS)</h5>
                    <small>Based on Speed: {{ $jobIssue->jobCard->ply_type == 5 ? $machineSpeed->speed_5ply : $machineSpeed->speed_3ply }} m/min</small>
                </div>
            </div>
        </div>

        <!-- PROCESS TRACKING -->
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Process Tracking</div>
                <div class="card-body">
                    @php
                        $processes = ['Printing', 'Rotary/Dic-Cut', 'QC'];
                    @endphp
                    @foreach($processes as $process)
                        @php
                            $track = $jobIssue->tracking->where('process_stage', $process)->first();
                        @endphp
                        <form action="{{ route('production.process.update', $jobIssue->id) }}" method="POST" class="mb-3 border-bottom pb-2">
                            @csrf
                            <input type="hidden" name="process_stage" value="{{ $process }}">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>{{ $process }}</strong>
                                <select name="status" class="form-control form-control-sm w-auto">
                                    <option value="Pending" {{ ($track && $track->status == 'Pending') ? 'selected' : '' }}>Pending</option>
                                    <option value="In Progress" {{ ($track && $track->status == 'In Progress') ? 'selected' : '' }}>In Progress</option>
                                    <option value="Completed" {{ ($track && $track->status == 'Completed') ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                            
                            <div class="row g-1 mb-2">
                                <div class="col-6">
                                    <select name="machine_id" class="form-control form-control-sm">
                                        <option value="">Select Machine</option>
                                        @foreach($machines as $machine)
                                            <option value="{{ $machine->id }}" {{ ($track && $track->machine_id == $machine->id) ? 'selected' : '' }}>{{ $machine->name }} ({{ $machine->type }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <select name="staff_id" class="form-control form-control-sm">
                                        <option value="">Select Operator</option>
                                        @foreach($staffs as $staff)
                                            <option value="{{ $staff->id }}" {{ ($track && $track->staff_id == $staff->id) ? 'selected' : '' }}>{{ $staff->name }} ({{ $staff->role }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row g-1 mb-2">
                                <div class="col-6">
                                    <input type="date" name="date" class="form-control form-control-sm" value="{{ $track && $track->date ? $track->date : date('Y-m-d') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="produced_qty" class="form-control form-control-sm" placeholder="Produced Qty" value="{{ $track ? $track->produced_qty : '' }}">
                                </div>
                            </div>
                            
                            @if($process == 'QC')
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="qc_approved" {{ ($track && $track->qc_approved) ? 'checked' : '' }}>
                                <label class="form-check-label">QC Approved</label>
                            </div>
                            @endif
                            <input type="text" name="remarks" class="form-control form-control-sm mt-1" placeholder="Remarks" value="{{ $track ? $track->remarks : '' }}">
                            <button type="submit" class="btn btn-sm btn-success mt-1 w-100">Update {{ $process }}</button>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- INVENTORY IN MODULE -->
        <div class="col-md-6">
             <div class="card mb-3">
                <div class="card-header bg-info text-white">Finished Goods Inventory In</div>
                <div class="card-body">
                    <form action="{{ route('production.inventory.store', $jobIssue->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                 <label>Date</label>
                                 <input type="date" name="date" class="form-control" required value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-4 mb-2">
                                 <label>Location</label>
                                 <select name="location" class="form-control">
                                    <option value="Warehouse A">Warehouse A</option>
                                    <option value="Warehouse B">Warehouse B</option>
                                    <option value="First Floor">First Floor</option>
                                    <option value="Second Floor">Second Floor</option>
                                    <option value="Dispatch Area">Dispatch Area</option>
                                 </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                 <label>Quantity In</label>
                                 <input type="number" name="qty_in" class="form-control" placeholder="Qty Cartons" required>
                            </div>
                        </div>
                        <div class="mb-2">
                             <input type="text" name="remarks" class="form-control" placeholder="Remarks/Shift">
                        </div>
                        <button type="submit" class="btn btn-info text-white w-100">Add to Stock</button>
                    </form>
                    
                    <h6 class="mt-3">Stock History</h6>
                    @php
                        $totalIn = $jobIssue->inventoryLogs->sum('qty_in');
                        $totalOut = $jobIssue->dispatches->sum('qty_dispatched');
                        $currentStock = $totalIn - $totalOut;
                    @endphp
                    <div class="alert alert-light border">
                        <strong>Current Stock: {{ $currentStock }} Cartons</strong>
                    </div>
                    
                    <ul class="list-group list-group-flush" style="max-height: 200px; overflow-y:auto;">
                        @foreach($jobIssue->inventoryLogs as $log)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $log->date }}</strong> <small class="text-muted">({{ $log->location ?? 'N/A' }})</small><br>
                                    <small>{{ $log->remarks }}</small>
                                </div>
                                <span class="badge bg-secondary rounded-pill">+{{ $log->qty_in }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
             </div>
        </div>

        <!-- DISPATCH MODULE -->
        <div class="col-md-6">
             <div class="card mb-3">
                <div class="card-header bg-success text-white">Dispatch</div>
                <div class="card-body">
                    <form action="{{ route('production.dispatch.store', $jobIssue->id) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                             <label>Date</label>
                             <input type="date" name="dispatch_date" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="mb-2">
                             <label>Quantity Dispatched</label>
                             <input type="number" name="qty_dispatched" class="form-control" required>
                        </div>
                        <div class="mb-2">
                             <label>DC Number</label>
                             <input type="text" name="dc_number" class="form-control" placeholder="QC-JC-00001" required>
                        </div>
                        <div class="mb-2">
                             <label>Vehicle No</label>
                             <input type="text" name="vehicle_no" class="form-control">
                        </div>
                        <div class="mb-2">
                             <label>Notes</label>
                             <textarea name="notes" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Dispatch Entry</button>
                    </form>
                    
                    <h6 class="mt-3">Dispatch History</h6>
                    <ul class="list-group">
                        @foreach($jobIssue->dispatches as $dispatch)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $dispatch->dispatch_date }}</strong> | <small>DC: {{ $dispatch->dc_number }}</small><br>
                                    <small>{{ $dispatch->vehicle_no }}</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $dispatch->qty_dispatched }} Cartons</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
             </div>
        </div>
@endsection
