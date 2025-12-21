@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Manage Corrugation: {{ $jobIssue->jobCard->job_no }}</h2>
        <a href="{{ route('corrugation.index') }}" class="btn btn-secondary shadow-sm">Back to List</a>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('warning')) <div class="alert alert-warning">{{ session('warning') }}</div> @endif

    <div class="row">
        <!-- Main Control Panel -->
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header bg-dark text-white">Job Controls</div>
                <div class="card-body">
                    @if(!$log)
                        <!-- START JOB FORM -->
                        <form action="{{ route('corrugation.start', $jobIssue->id) }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                @if($jobIssue->jobCard->ply_type > 3)
                                <div class="col-md-4">
                                    <label>Machine 1</label>
                                    <select name="machine_id" class="form-control" required>
                                        <option value="">-- Select --</option>
                                        @foreach($machines as $m)
                                            <option value="{{ $m->id }}">{{ $m->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Machine 2</label>
                                    <select name="machine_id_2" class="form-control" required>
                                        <option value="">-- Select --</option>
                                        @foreach($machines as $m)
                                            <option value="{{ $m->id }}">{{ $m->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                @else
                                <div class="col-md-6">
                                    <label>Select Corrugation Machine</label>
                                    <select name="machine_id" class="form-control" required>
                                        <option value="">-- Select Machine --</option>
                                        @foreach($machines as $m)
                                            <option value="{{ $m->id }}">{{ $m->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                @endif
                                    <label>Shift In-Charge / Operator</label>
                                    <select name="staff_id" class="form-control" required>
                                        <option value="">-- Select Staff --</option>
                                        @foreach($staffs as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->role }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-lg btn-success w-100"><i class="fas fa-play"></i> Start Job</button>
                        </form>
                    @elseif(!$log->end_time)
                        <!-- RUNNING JOB -->
                        <div class="alert alert-info">
                            <strong>Job Started At:</strong> {{ \Carbon\Carbon::parse($log->start_time)->format('d-M-Y H:i A') }}<br>
                            <strong>Running Duration:</strong> {{ \Carbon\Carbon::parse($log->start_time)->diffForHumans(null, true) }}
                        </div>

                        <!-- Time Session Management -->
                        <div class="card mb-3 border-primary">
                            <div class="card-header bg-primary text-white">
                                <i class="fas fa-clock"></i> Work Time Sessions
                            </div>
                            <div class="card-body">
                                @php
                                    $totalSessionTime = $log->timeSessions->sum('duration_minutes');
                                @endphp
                                
                                <button class="btn btn-success w-100 mb-3" data-bs-toggle="modal" data-bs-target="#addTimeSessionModal">
                                    <i class="fas fa-plus"></i> Add Work Time Session
                                </button>

                                @if($log->timeSessions->count() > 0)
                                <h6>Recorded Sessions:</h6>
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Start</th>
                                            <th>End</th>
                                            <th>Duration</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($log->timeSessions as $session)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($session->session_start)->format('d-M h:i A') }}</td>
                                            <td>{{ $session->session_end ? \Carbon\Carbon::parse($session->session_end)->format('d-M h:i A') : 'Ongoing' }}</td>
                                            <td>{{ $session->duration_minutes ? $session->duration_minutes . ' min' : '-' }}</td>
                                            <td><small>{{ $session->notes ?? '-' }}</small></td>
                                        </tr>
                                        @endforeach
                                        <tr class="table-info">
                                            <th colspan="2">Total Work Time</th>
                                            <th colspan="2">{{ $totalSessionTime }} minutes ({{ round($totalSessionTime / 60, 1) }} hrs)</th>
                                        </tr>
                                    </tbody>
                                </table>
                                @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-info-circle"></i> No work sessions recorded yet. Click "Add Work Time Session" to record work periods.
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- OPERATING CONTROLS -->
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-danger w-100 mb-3" data-bs-toggle="modal" data-bs-target="#downtimeModal">
                                    <i class="fas fa-plus-circle"></i> Add Downtime Record
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-warning w-100 mb-3" data-bs-toggle="modal" data-bs-target="#wastageModal">
                                    <i class="fas fa-trash-alt"></i> Log Wastage
                                </button>
                            </div>
                        </div>

                        <hr>
                        <form action="{{ route('corrugation.end', $log->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to finish this job? This will calculate final speed and close the log.');">
                            @csrf
                            <div class="mb-3">
                                <label>Total Sheets Produced</label>
                                <input type="number" name="total_sheets_produced" class="form-control form-control-lg" placeholder="Enter Final Count" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100"><i class="fas fa-check-circle"></i> Finish Job</button>
                        </form>
                    @else
                        <!-- COMPLETED -->
                        <div class="alert alert-success">
                            <h4>Job Completed</h4>
                            <p><strong>Start:</strong> {{ $log->start_time }}</p>
                            <p><strong>End:</strong> {{ $log->end_time }}</p>
                            <p><strong>Sheets:</strong> {{ $log->total_sheets_produced }}</p>
                            <p><strong>Avg Speed:</strong> {{ round($log->avg_speed_mpm, 2) }} m/min</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">Job Details</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Item:</strong> {{ $jobIssue->jobCard->item_name }}</li>
                    <li class="list-group-item"><strong>Size:</strong> 
                        {{ ($jobIssue->jobCard->uom == 'mm' ? number_format($jobIssue->jobCard->length, 0) : number_format($jobIssue->jobCard->length, 2)) }}x
                        {{ ($jobIssue->jobCard->uom == 'mm' ? number_format($jobIssue->jobCard->width, 0) : number_format($jobIssue->jobCard->width, 2)) }}x
                        {{ ($jobIssue->jobCard->uom == 'mm' ? number_format($jobIssue->jobCard->height, 0) : number_format($jobIssue->jobCard->height, 2)) }} 
                        {{ $jobIssue->jobCard->uom }}
                    </li>
                    <li class="list-group-item"><strong>Req Sheets:</strong> {{ ceil($jobIssue->order_qty_cartons / ($jobIssue->jobCard->ups > 0 ? $jobIssue->jobCard->ups : 1)) }}</li>
                    <li class="list-group-item"><strong>Sheet Length:</strong> {{ $jobIssue->jobCard->sheet_length }} Inch</li>
                </ul>
            </div>

            <!-- REEL CONSUMPTION -->
            <div class="card mb-3">
                <div class="card-header">Reel Data Consumption</div>
                <div class="card-body">
                    <form action="{{ route('production.reel.store', $jobIssue->id) }}" method="POST" class="row g-2">
                        @csrf
                        <div class="col-md-3">
                            <select name="usage_type" class="form-control form-control-sm" required>
                                <option value="">Usage</option>
                                <option value="Inner Liner">Inner</option>
                                <option value="Outer Liner">Outer</option>
                                <option value="Flute B">Flute B</option>
                                <option value="Flute C">Flute C</option>
                                <option value="Flute E">Flute E</option>
                                <option value="Middle Liner">Middle</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="reel_number" class="form-control form-control-sm" placeholder="Reel #" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" step="0.01" name="weight" class="form-control form-control-sm" placeholder="Weight" required>
                        </div>
                        <div class="col-md-2">
                             <button type="submit" class="btn btn-primary btn-sm w-100">+</button>
                        </div>
                    </form>
                    <hr class="my-2">
                    <table class="table table-sm table-bordered mb-0">
                        <thead><tr><th>Reel #</th><th>Type</th><th>Weight</th></tr></thead>
                        <tbody>
                            @foreach($jobIssue->reels as $reel)
                            <tr><td>{{ $reel->reel_number }}</td><td>{{ $reel->usage_type }}</td><td>{{ $reel->weight }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($log)
            <div class="card mb-3">
                <div class="card-header">Downtime Logs</div>
                <div class="card-body p-0">
                   <table class="table table-sm mb-0">
                       <thead><tr><th>Reason</th><th>Period</th><th>Dur</th></tr></thead>
                       <tbody>
                           @foreach($log->downtimes as $dt)
                           <tr>
                               <td>{{ $dt->reason }}</td>
                               <td><small>{{ \Carbon\Carbon::parse($dt->start_time)->format('H:i') }}-{{ $dt->end_time ? \Carbon\Carbon::parse($dt->end_time)->format('H:i') : '' }}</small></td>
                               <td>{{ $dt->duration_minutes }} m</td>
                           </tr>
                           @endforeach
                           <tr>
                               <th colspan="2">Total</th>
                               <th>{{ $log->downtimes->sum('duration_minutes') }} m</th>
                           </tr>
                       </tbody>
                   </table>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-header">Wastage Logs</div>
                <div class="card-body p-0">
                   <table class="table table-sm mb-0">
                       <thead><tr><th>Type</th><th>Qty</th><th>By</th></tr></thead>
                       <tbody>
                           @foreach($log->wastages as $w)
                           <tr>
                               <td>{{ $w->type }}</td>
                               <td>{{ $w->quantity }} {{ $w->unit }}</td>
                               <td>{{ $w->staff ? $w->staff->name : '-' }}</td>
                           </tr>
                           @endforeach
                       </tbody>
                   </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Initial Time Setup Modal (shown on first manage) -->
@if(isset($needsTimeSetup) && $needsTimeSetup)
<div class="modal fade" id="initialTimeSetupModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <form action="{{ route('corrugation.time-session.add', $log->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-clock"></i> Set Work Time</h5>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Important:</strong> Please enter the actual work period times. If the job spans multiple days, you can add additional sessions later.
                    </div>
                    <div class="mb-3">
                        <label>Session Start Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="session_start" class="form-control" required>
                        <small class="text-muted">When did work actually start?</small>
                    </div>
                    <div class="mb-3">
                        <label>Session End Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="session_end" class="form-control" required>
                        <small class="text-muted">When did this work period end?</small>
                    </div>
                    <div class="mb-3">
                        <label>Notes (Optional)</label>
                        <input type="text" name="notes" class="form-control" placeholder="e.g., Day 1 shift, Evening work">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save & Continue</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Add Time Session Modal -->
@if($log && !$log->end_time)
<div class="modal fade" id="addTimeSessionModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('corrugation.time-session.add', $log->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Add Time Session</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Tip:</strong> Enter the actual work period times. For example, if work was done from 5:00 PM to 8:00 PM on Day 1, and 10:00 AM to 1:30 PM on Day 2, add them as separate sessions.
                    </div>
                    <div class="mb-3">
                        <label>Session Start Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="session_start" class="form-control" required>
                        <small class="text-muted">When did this work period start?</small>
                    </div>
                    <div class="mb-3">
                        <label>Session End Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="session_end" class="form-control" required>
                        <small class="text-muted">When did this work period end?</small>
                    </div>
                    <div class="mb-3">
                        <label>Notes (Optional)</label>
                        <input type="text" name="notes" class="form-control" placeholder="e.g., Day 1 evening shift, Day 2 morning shift">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Session</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Downtime Modal -->
<div class="modal fade" id="downtimeModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('corrugation.downtime.store', $log->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Record Downtime</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Reason for Stop</label>
                        <select name="reason" class="form-control" required>
                            <option value="Paper Reel Change">Paper Reel Change</option>
                            <option value="Paper Breakage">Paper Breakage</option>
                            <option value="Machine Fault">Machine Fault</option>
                            <option value="Power Failure">Power Failure</option>
                            <option value="Lunch/Tea Break">Lunch/Tea Break</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label>Down Start Time</label>
                            <input type="datetime-local" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label>Down End Time</label>
                            <input type="datetime-local" name="end_time" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Save Record</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Wastage Modal -->
<div class="modal fade" id="wastageModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('corrugation.wastage', $log->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">Record Wastage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Wastage Type</label>
                        <select name="type" class="form-control" required>
                            <option value="Paper">Paper</option>
                            <option value="Flute">Flute</option>
                            <option value="Sheet">Finished Sheet</option>
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label>Quantity</label>
                            <input type="number" step="0.01" name="quantity" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label>Unit</label>
                            <select name="unit" class="form-control">
                                <option value="kg">kg</option>
                                <option value="sheets">Sheets</option>
                                <option value="meter">Meter</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Responsible Staff</label>
                        <select name="staff_id" class="form-control">
                            <option value="">-- Select --</option>
                            @foreach($staffs as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Reason / Note</label>
                        <input type="text" name="reason" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Save Wastage</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    // Auto-show initial time setup modal if needed
    @if(isset($needsTimeSetup) && $needsTimeSetup)
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('initialTimeSetupModal'));
        modal.show();
    });
    @endif
</script>
@endpush
