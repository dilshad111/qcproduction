<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobIssue;
use App\Models\CorrugationLog;
use App\Models\DowntimeLog;
use App\Models\WastageLog;
use App\Models\Machine;
use App\Models\Staff;
use Carbon\Carbon;

class CorrugationController extends Controller
{
    // List available jobs for corrugation
    public function index()
    {
        // Jobs pending or in-progress at corrugation stage
        // Assuming every job needs corrugation
        $jobIssues = JobIssue::with('jobCard', 'customer')
            ->whereNotIn('status', ['Completed', 'Dispatched'])
            ->latest()
            ->get();
            
        return view('corrugation.index', compact('jobIssues'));
    }

    // Main dashboard for a specific job
    public function manage($id)
    {
        $jobIssue = JobIssue::with('jobCard', 'reels')->findOrFail($id);
        
        $log = CorrugationLog::where('job_issue_id', $jobIssue->id)->latest()->first();
        
        $machines = Machine::where('type', 'Corrugator')->get();
        if($machines->isEmpty()){
             $machines = Machine::all(); 
        }
        $staffs = Staff::all();

        return view('corrugation.manage', compact('jobIssue', 'log', 'machines', 'staffs'));
    }

    public function startJob(Request $request, $id)
    {
        $jobIssue = JobIssue::findOrFail($id);
        
        CorrugationLog::create([
            'job_issue_id' => $jobIssue->id,
            'machine_id' => $request->machine_id,
            'machine_id_2' => $request->machine_id_2,
            'staff_id' => $request->staff_id,
            'start_time' => Carbon::now(),
        ]);
        
        return back()->with('success', 'Job Corrugation Started');
    }

    public function endJob(Request $request, $id)
    {
        $log = CorrugationLog::findOrFail($id);
        $log->end_time = Carbon::now();
        $log->total_sheets_produced = $request->total_sheets_produced;
        
        // Calculate Speed
        $start = Carbon::parse($log->start_time);
        $end = Carbon::parse($log->end_time);
        
        // Subtract total downtime
        $downtimeMinutes = $log->downtimes()->sum('duration_minutes');
        $totalDuration = $end->diffInMinutes($start);
        $runTime = max(1, $totalDuration - $downtimeMinutes);
        
        // Sheet Length is in Inches (based on user feedback)
        $sheetLengthMeters = $log->jobIssue->jobCard->sheet_length * 0.0254;
        $totalMeters = $sheetLengthMeters * $log->total_sheets_produced;
        
        $log->avg_speed_mpm = $totalMeters / $runTime;
        $log->save();
        
        return back()->with('success', 'Job Corrugation Finished. Speed: ' . round($log->avg_speed_mpm, 2) . ' m/min');
    }

    public function storeDowntime(Request $request, $id)
    {
        $log = CorrugationLog::findOrFail($id);
        
        $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'reason' => 'required'
        ]);

        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $duration = $end->diffInMinutes($start);
        
        DowntimeLog::create([
            'corrugation_log_id' => $log->id,
            'reason' => $request->reason,
            'start_time' => $start,
            'end_time' => $end,
            'duration_minutes' => $duration
        ]);
        
        return back()->with('success', 'Downtime Recorded.');
    }

    public function logWastage(Request $request, $id)
    {
        $log = CorrugationLog::findOrFail($id);
        
        WastageLog::create([
            'corrugation_log_id' => $log->id,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'staff_id' => $request->staff_id, // Who wasted it
            'reason' => $request->reason
        ]);
        
        return back()->with('success', 'Wastage Logged');
    }
    
    public function report(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');
        
        $logs = CorrugationLog::with(['jobIssue.jobCard', 'downtimes', 'wastages'])
            ->whereDate('start_time', $date)
            ->get();
            
        return view('corrugation.report', compact('logs', 'date'));
    }
}
