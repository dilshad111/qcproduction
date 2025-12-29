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
        $issues = JobIssue::with('customer', 'jobCard')->latest()->get();
        return view('production.issue_job', compact('customers', 'issues'));
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
        // Formula: Required Sheet Qty = Carton Qty / ups
        $ups = $jobCard->ups > 0 ? $jobCard->ups : 1;
        $requiredSheets = ceil($request->order_qty_cartons / $ups);

        // Generate Issue Number
        $issueNo = \App\Models\JobIssueNumberSetup::getNextIssueNumber();

        $issue = JobIssue::create([
            'issue_no' => $issueNo,
            'customer_id' => $request->customer_id,
            'job_card_id' => $request->job_card_id,
            'po_number' => $request->po_number,
            'order_qty_cartons' => $request->order_qty_cartons,
            'required_sheet_qty' => $requiredSheets,
            'status' => 'Pending'
        ]);

        return redirect()->route('production.create')->with('success', 'Job Issued for Production. Issue No: ' . $issueNo . ' | Required Sheets: ' . $requiredSheets);
    }

    public function edit($id)
    {
        $issue = JobIssue::findOrFail($id);
        $customers = Customer::all();
        $issues = JobIssue::with('customer', 'jobCard')->latest()->get();
        return view('production.issue_job', compact('customers', 'issues', 'issue'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required',
            'job_card_id' => 'required',
            'order_qty_cartons' => 'required|numeric',
            'po_number' => 'required'
        ]);

        $issue = JobIssue::findOrFail($id);
        $jobCard = JobCard::find($request->job_card_id);
        
        // Calculate Sheet Qty
        $ups = $jobCard->ups > 0 ? $jobCard->ups : 1;
        $requiredSheets = ceil($request->order_qty_cartons / $ups);

        $issue->update([
            'customer_id' => $request->customer_id,
            'job_card_id' => $request->job_card_id,
            'po_number' => $request->po_number,
            'order_qty_cartons' => $request->order_qty_cartons,
            'required_sheet_qty' => $requiredSheets
        ]);

        return redirect()->route('production.create')->with('success', 'Job Issue Updated Successfully!');
    }

    public function destroy($id)
    {
        $issue = JobIssue::findOrFail($id);
        $issue->delete();
        return redirect()->route('production.create')->with('success', 'Job Issue Deleted Successfully!');
    }

    public function print($id)
    {
        $issue = JobIssue::with(['customer', 'jobCard.pieces.layers', 'jobCard.cartonType'])->findOrFail($id);
        $company = \App\Models\Company::first();
        
        return view('production.print_issue', compact('issue', 'company'));
    }
}
