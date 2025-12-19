<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobIssue;
use App\Models\ProductionTracking;
use App\Models\MachineSpeed;
use App\Models\Dispatch;

use App\Models\Machine;
use App\Models\Staff;

class ProductionTrackingController extends Controller
{
    public function manage(JobIssue $jobIssue)
    {
        $jobIssue->load(['jobCard', 'customer', 'reels', 'tracking']);
        
        // Time Estimation Logic
        $ups = $jobIssue->jobCard->ups > 0 ? $jobIssue->jobCard->ups : 1;
        $reqSheets = ceil($jobIssue->order_qty_cartons / $ups);

        $sheetLengthMeters = $jobIssue->jobCard->sheet_length * 0.0254;
        $totalLength = $sheetLengthMeters * $reqSheets;
        
        $machineSpeed = MachineSpeed::first();
        $speed = $jobIssue->jobCard->ply_type == 5 ? $machineSpeed->speed_5ply : $machineSpeed->speed_3ply;
        
        $estimatedTime = 0;
        if($speed > 0) {
            $estimatedMinutes = $totalLength / $speed;
            $estimatedTime = gmdate("H:i:s", $estimatedMinutes * 60);
        }

        // Available Processes Logic
        $processes = [];
        $processes[] = 'Corrugation (Input)'; // New input step as requested
        
        // Printing
        if ($jobIssue->jobCard->print_colors > 0) {
            $processes[] = 'Printing';
        }
        
        // Cutting / Slotting
        // Check Job Card process type. If not set, default to Die Cutting?
        // User said: "If any carton is not die cut then Die cut option should not be avaliable"
        // "rottary slotter option should be avalable"
        if ($jobIssue->jobCard->process_type == 'Rotary Slotter') {
            $processes[] = 'Rotary Slotter';
        } else {
            $processes[] = 'Die Cutting';
        }
        
        $processes[] = 'Pasting/Stitching'; // Generic term, could be specific
        $processes[] = 'Bundling';
        
        $machines = Machine::where('status', 1)->get();
        $staffs = Staff::where('status', 1)->get();

        return view('production.manage', compact('jobIssue', 'estimatedTime', 'machineSpeed', 'processes', 'machines', 'staffs'));
    }

    public function storeReel(Request $request, JobIssue $jobIssue)
    {
        $request->validate([
            'reel_number' => 'required',
            'usage_type' => 'required',
            'weight' => 'required|numeric'
        ]);

        $jobIssue->reels()->create($request->all());
        return back()->with('success', 'Reel Data Added.');
    }

    public function updateProcess(Request $request, JobIssue $jobIssue)
    {
        $request->validate([
            'process_stage' => 'required', 
            'status' => 'required',
            'produced_qty' => 'nullable|numeric'
        ]);
        
        ProductionTracking::updateOrCreate(
            ['job_issue_id' => $jobIssue->id, 'process_stage' => $request->process_stage],
            [
                'status' => $request->status, 
                'produced_qty' => $request->produced_qty ?? 0,
                'remarks' => $request->remarks,
                'machine_id' => $request->machine_id,
                'staff_id' => $request->staff_id,
                'date' => $request->date ?? date('Y-m-d'),
                'qc_approved' => $request->has('qc_approved')
            ]
        );

        return back()->with('success', 'Process Updated.');
    }

    public function storeInventoryLog(Request $request, JobIssue $jobIssue)
    {
        $request->validate([
            'date' => 'required|date',
            'qty_in' => 'required|numeric|min:1',
            'location' => 'required',
            'remarks' => 'nullable|string'
        ]);

        $jobIssue->inventoryLogs()->create([
            'qty_in' => $request->qty_in,
            'location' => $request->location,
            'date' => $request->date,
            'remarks' => $request->remarks
        ]);

        return back()->with('success', 'Inventory Added.');
    }

    public function storeDispatch(Request $request, JobIssue $jobIssue)
    {
        $request->validate([
            'dispatch_date' => 'required|date',
            'dc_number' => 'required|string|max:50',
            'qty_dispatched' => 'required|numeric|min:1',
            'vehicle_no' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        // Calculate available stock
        $totalIn = $jobIssue->inventoryLogs()->sum('qty_in');
        $totalOut = $jobIssue->dispatches()->sum('qty_dispatched');
        $available = $totalIn - $totalOut;

        if ($request->qty_dispatched > $available) {
            return back()->with('error', 'Insufficient Inventory! Available: ' . $available . ' Cartons');
        }

        Dispatch::create([
            'job_issue_id' => $jobIssue->id,
            'dc_number' => $request->dc_number,
            'dispatch_date' => $request->dispatch_date,
            'qty_dispatched' => $request->qty_dispatched,
            'vehicle_no' => $request->vehicle_no,
            'notes' => $request->notes
        ]);

        return back()->with('success', 'Dispatch Record Added.');
    }
}
