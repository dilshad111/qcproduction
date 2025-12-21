<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobIssue;
use App\Models\CorrugationLog;
use App\Models\CorrugationTimeSession;
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
        
        $log = CorrugationLog::with('timeSessions')->where('job_issue_id', $jobIssue->id)->latest()->first();
        
        // Get machines from Corrugation Plant department
        $machines = Machine::where('department', 'Corrugation Plant')->get();
        if($machines->isEmpty()){
             // Fallback to Corrugator type if no department machines found
             $machines = Machine::where('type', 'Corrugator')->get(); 
        }
        
        // Get staff from Corrugation Plant department
        $staffs = Staff::where('department', 'Corrugation Plant')->get();
        if($staffs->isEmpty()){
             // Fallback to all staff if no department staff found
             $staffs = Staff::all();
        }

        // Check if we need to show time setup modal
        $needsTimeSetup = $log && $log->timeSessions->isEmpty();

        return view('corrugation.manage', compact('jobIssue', 'log', 'machines', 'staffs', 'needsTimeSetup'));
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
        $log = CorrugationLog::with('timeSessions')->findOrFail($id);
        
        // Close any active session
        $activeSession = $log->timeSessions()->whereNull('session_end')->latest()->first();
        if ($activeSession) {
            $end = Carbon::now();
            $activeSession->session_end = $end;
            $activeSession->duration_minutes = $end->diffInMinutes($activeSession->session_start);
            $activeSession->save();
        }
        
        $log->end_time = Carbon::now();
        $log->total_sheets_produced = $request->total_sheets_produced;
        
        // Calculate Speed based on actual work time from sessions
        // Total work time = sum of all session durations
        $totalWorkMinutes = $log->timeSessions()->sum('duration_minutes');
        
        // Subtract downtime from work time
        $downtimeMinutes = $log->downtimes()->sum('duration_minutes');
        $runTime = max(1, $totalWorkMinutes - $downtimeMinutes);
        
        // Sheet Length is in Inches (based on user feedback)
        $sheetLengthMeters = $log->jobIssue->jobCard->sheet_length * 0.0254;
        $totalMeters = $sheetLengthMeters * $log->total_sheets_produced;
        
        $log->avg_speed_mpm = $totalMeters / $runTime;
        $log->save();
        
        return back()->with('success', 'Job Corrugation Finished. Speed: ' . round($log->avg_speed_mpm, 2) . ' m/min (Work Time: ' . round($totalWorkMinutes / 60, 1) . ' hrs)');
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

    public function addTimeSession(Request $request, $id)
    {
        $log = CorrugationLog::findOrFail($id);
        
        $request->validate([
            'session_start' => 'required|date',
            'session_end' => 'required|date|after:session_start',
        ]);

        $start = Carbon::parse($request->session_start);
        $end = Carbon::parse($request->session_end);
        
        $duration = $end->diffInMinutes($start);

        CorrugationTimeSession::create([
            'corrugation_log_id' => $log->id,
            'session_start' => $start,
            'session_end' => $end,
            'duration_minutes' => $duration,
            'notes' => $request->notes
        ]);
        
        return back()->with('success', 'Time session recorded successfully');
    }

    public function pauseSession(Request $request, $id)
    {
        $log = CorrugationLog::findOrFail($id);
        
        // Find the active session (one without end time)
        $activeSession = $log->timeSessions()->whereNull('session_end')->latest()->first();
        
        if ($activeSession) {
            $end = Carbon::now();
            $activeSession->session_end = $end;
            $activeSession->duration_minutes = $end->diffInMinutes($activeSession->session_start);
            $activeSession->save();
            
            return back()->with('success', 'Work session paused');
        }
        
        return back()->with('warning', 'No active session to pause');
    }

    public function resumeSession(Request $request, $id)
    {
        $log = CorrugationLog::findOrFail($id);
        
        $request->validate([
            'session_start' => 'required|date',
        ]);

        CorrugationTimeSession::create([
            'corrugation_log_id' => $log->id,
            'session_start' => Carbon::parse($request->session_start),
            'session_end' => null,
            'duration_minutes' => null,
            'notes' => 'Resumed session'
        ]);
        
        return back()->with('success', 'Work session resumed');
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
