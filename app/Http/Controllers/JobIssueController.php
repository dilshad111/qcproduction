<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobIssue;
use App\Models\Customer;
use App\Models\JobCard;

class JobIssueController extends Controller
{
    public function index()
    {
        $issues = JobIssue::with('customer', 'jobCard')->latest()->get();
        return view('production.index', compact('issues'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('production.issue_job', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'job_card_id' => 'required',
            'order_qty_cartons' => 'required|numeric',
            'po_number' => 'required'
        ]);

        $jobCard = JobCard::find($request->job_card_id);
        
        // Calculate Sheet Qty
        // Formula: Required Sheet Qty = Carton Qty / UP
        $requiredSheets = ceil($request->order_qty_cartons / $jobCard->ups);

        $issue = JobIssue::create([
            'customer_id' => $request->customer_id,
            'job_card_id' => $request->job_card_id,
            'po_number' => $request->po_number,
            'order_qty_cartons' => $request->order_qty_cartons,
            'required_sheet_qty' => $requiredSheets,
            'status' => 'Pending'
        ]);

        return redirect()->route('production.index')->with('success', 'Job Issued for Production. Required Sheets: ' . $requiredSheets);
    }
}
