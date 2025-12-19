<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobIssue;
use App\Models\ProductionTracking;
use App\Models\MachineSpeed;
use App\Models\Dispatch;

class ProductionTrackingController extends Controller
{
    public function manage(JobIssue $jobIssue)
    {
        $jobIssue->load(['jobCard', 'customer', 'reels', 'tracking']);
        
        // Time Estimation Logic
        $sheetLengthMeters = $jobIssue->jobCard->sheet_length * 0.0254;
        $totalLength = $sheetLengthMeters * $jobIssue->required_sheet_qty;
        
        $machineSpeed = MachineSpeed::first();
        $speed = $jobIssue->jobCard->ply_type == 5 ? $machineSpeed->speed_5ply : $machineSpeed->speed_3ply;
        
        $estimatedTime = 0;
        if($speed > 0) {
            $estimatedMinutes = $totalLength / $speed;
            $estimatedTime = gmdate("H:i:s", $estimatedMinutes * 60);
        }

        return view('production.manage', compact('jobIssue', 'estimatedTime', 'machineSpeed'));
    }

    public function storeReel(Request $request, JobIssue $jobIssue)
    {
        $request->validate([
            'reel_number' => 'required',
            'weight' => 'required|numeric'
        ]);

        $jobIssue->reels()->create($request->all());
        return back()->with('success', 'Reel Data Added.');
    }

    public function updateProcess(Request $request, JobIssue $jobIssue)
    {
        $request->validate(['process_stage' => 'required', 'status' => 'required']);
        
        ProductionTracking::updateOrCreate(
            ['job_issue_id' => $jobIssue->id, 'process_stage' => $request->process_stage],
            ['status' => $request->status, 'remarks' => $request->remarks, 'qc_approved' => $request->has('qc_approved')]
        );

        return back()->with('success', 'Process Updated.');
    }

    public function storeDispatch(Request $request, JobIssue $jobIssue)
    {
        $request->validate([
            'dispatch_date' => 'required|date',
            'qty_dispatched' => 'required|numeric|min:1',
            'vehicle_no' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        Dispatch::create([
            'job_issue_id' => $jobIssue->id,
            'dispatch_date' => $request->dispatch_date,
            'qty_dispatched' => $request->qty_dispatched,
            'vehicle_no' => $request->vehicle_no,
            'notes' => $request->notes
        ]);

        return back()->with('success', 'Dispatch Record Added.');
    }
}
