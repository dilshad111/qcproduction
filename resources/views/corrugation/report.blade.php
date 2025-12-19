@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Daily Corrugation Report</h2>
        <form method="GET" class="d-flex">
            <input type="date" name="date" class="form-control me-2" value="{{ $date }}">
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                                <th>Job No</th>
                            <th>Machine</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Total Time</th>
                            <th>Est. Time</th>
                            <th>Variance</th>
                            <th>Production</th>
                            <th>Speed (m/min)</th>
                            <th>Wastage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            @php
                                $start = \Carbon\Carbon::parse($log->start_time);
                                $end = $log->end_time ? \Carbon\Carbon::parse($log->end_time) : null;
                                $totalMinutes = $end ? $end->diffInMinutes($start) : '-';
                                $downMinutes = $log->downtimes->sum('duration_minutes');
                                $netRun = is_numeric($totalMinutes) ? ($totalMinutes - $downMinutes) : '-';
                                
                                // Estimate
                                $ply = $log->jobIssue->jobCard->ply_type;
                                $ms = \App\Models\MachineSpeed::first();
                                $stdSpeed = $ms ? ($ply == 5 ? $ms->speed_5ply : $ms->speed_3ply) : 100;
                                
                                // Sheet Length in Meters (Assuming Inch)
                                $sheetInches = $log->jobIssue->jobCard->sheet_length;
                                $sheetMeters = $sheetInches * 0.0254; 
                                
                                $totalLinMeters = $log->total_sheets_produced * $sheetMeters;
                                $estMinutes = $stdSpeed > 0 ? round($totalLinMeters / $stdSpeed) : 0;
                                
                                $variance = '-';
                                $varianceColor = '';
                                if(is_numeric($netRun)){
                                    $diff = $netRun - $estMinutes;
                                    if($diff > 0) {
                                        $variance = '+' . $diff . ' min (Late)';
                                        $varianceColor = 'text-danger';
                                    } else {
                                        $variance = $diff . ' min (Early)';
                                        $varianceColor = 'text-success';
                                    }
                                }

                                $wastageText = [];
                                foreach($log->wastages as $w){
                                    $wastageText[] = $w->type . ': ' . $w->quantity . $w->unit;
                                }
                            @endphp
                        <tr>
                            <td>{{ $log->jobIssue->jobCard->job_no }}</td>
                            <td>{{ $log->machine_id ? \App\Models\Machine::find($log->machine_id)->name : '-' }}</td>
                            <td>{{ $start->format('H:i') }}</td>
                            <td>{{ $end ? $end->format('H:i') : 'Runs' }}</td>
                            <td>{{ $totalMinutes }} min</td>
                            <td>{{ $estMinutes }} min</td>
                            <td class="{{ $varianceColor }}">{{ $variance }}</td>
                            <td>{{ $log->total_sheets_produced }} sheets</td>
                            <td>{{ $log->avg_speed_mpm ? round($log->avg_speed_mpm, 2) : '-' }}</td>
                            <td>
                                <small>{{ implode(', ', $wastageText) }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">No production records found for {{ $date }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
