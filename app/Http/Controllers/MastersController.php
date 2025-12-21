<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ink;
use App\Models\CartonType;
use App\Models\MachineSpeed;
use App\Models\JobNumberSetup;
use App\Models\JobIssueNumberSetup;
use App\Models\Paper;
use App\Models\Machine;
use App\Models\Staff;

class MastersController extends Controller
{
    public function index()
    {
        $inks = Ink::all();
        $cartonTypes = CartonType::all();
        $papers = Paper::all();
        $machines = Machine::all();
        $staffs = Staff::all(); // Or filter by valid roles
        $machineSpeed = MachineSpeed::firstOrCreate([], ['speed_3ply' => 0, 'speed_5ply' => 0]);
        $jobNumberSetup = JobNumberSetup::firstOrCreate([], ['prefix' => 'JC-', 'current_number' => 0, 'padding' => 5]);
        $jobIssueNumberSetup = JobIssueNumberSetup::firstOrCreate([], ['prefix' => 'JI-', 'current_number' => 0, 'padding' => 5]);
        
        return view('masters.index', compact('inks', 'cartonTypes', 'papers', 'machines', 'staffs', 'machineSpeed', 'jobNumberSetup', 'jobIssueNumberSetup'));
    }

    public function storeInk(Request $request)
    {
        $request->validate(['color_name' => 'required', 'color_code' => 'nullable']);
        Ink::create($request->all());
        return back()->with('success', 'Ink added successfully.')->with('active_tab', 'inks');
    }

    public function updateInk(Request $request, Ink $ink)
    {
        $request->validate(['color_name' => 'required', 'color_code' => 'nullable']);
        $ink->update($request->all());
        return back()->with('success', 'Ink updated successfully.')->with('active_tab', 'inks');
    }

    public function destroyInk(Ink $ink)
    {
        $ink->delete();
        return back()->with('success', 'Ink deleted.')->with('active_tab', 'inks');
    }

    public function storeCartonType(Request $request)
    {
        $request->validate(['name' => 'required']);
        CartonType::create($request->all());
        return back()->with('success', 'Carton Type added.')->with('active_tab', 'carton');
    }

    public function updateCartonType(Request $request, CartonType $cartonType)
    {
        $request->validate(['name' => 'required']);
        $cartonType->update($request->all());
        return back()->with('success', 'Carton Type updated.')->with('active_tab', 'carton');
    }

    public function destroyCartonType(CartonType $cartonType)
    {
        $cartonType->delete();
        return back()->with('success', 'Carton Type deleted.')->with('active_tab', 'carton');
    }

    public function updateMachineSpeed(Request $request)
    {
        $machineSpeed = MachineSpeed::first();
        $machineSpeed->update($request->all());
        return back()->with('success', 'Machine Speed updated.')->with('active_tab', 'speed');
    }

    public function storeJobNumberSetup(Request $request)
    {
        $request->validate([
            'job_number' => 'required|string'
        ]);

        $result = JobNumberSetup::parseAndSetup($request->job_number);
        
        if ($result) {
            return back()->with('success', 'Job Card Number Setup configured successfully.')->with('active_tab', 'jobnumber');
        } else {
            return back()->with('error', 'Invalid job number format. Please use format like "JC-QC-00001".')->with('active_tab', 'jobnumber');
        }
    }

    public function storeJobIssueNumberSetup(Request $request)
    {
        $request->validate([
            'issue_number' => 'required|string'
        ]);

        $result = JobIssueNumberSetup::parseAndSetup($request->issue_number);
        
        if ($result) {
            return back()->with('success', 'Job Issue Number Setup configured successfully.')->with('active_tab', 'issuenumber');
        } else {
            return back()->with('error', 'Invalid issue number format. Please use format like "JI-00001".')->with('active_tab', 'issuenumber');
        }
    }

    public function storePaper(Request $request)
    {
        $request->validate(['name' => 'required', 'gsm' => 'required|integer']);
        Paper::create($request->all());
        return back()->with('success', 'Paper added successfully.')->with('active_tab', 'papers');
    }

    public function updatePaper(Request $request, Paper $paper)
    {
        $request->validate(['name' => 'required', 'gsm' => 'required|integer']);
        $paper->update($request->all());
        return back()->with('success', 'Paper updated successfully.')->with('active_tab', 'papers');
    }

    public function destroyPaper(Paper $paper)
    {
        $paper->delete();
        return back()->with('success', 'Paper deleted.')->with('active_tab', 'papers');
    }

    public function storeMachine(Request $request)
    {
        $request->validate(['name' => 'required', 'type' => 'required', 'department' => 'required']);
        Machine::create($request->all());
        return back()->with('success', 'Machine added.')->with('active_tab', 'machines');
    }

    public function updateMachine(Request $request, Machine $machine)
    {
        $request->validate(['name' => 'required', 'type' => 'required', 'department' => 'required']);
        $machine->update($request->all());
        return back()->with('success', 'Machine updated.')->with('active_tab', 'machines');
    }

    public function destroyMachine(Machine $machine)
    {
        $machine->delete();
        return back()->with('success', 'Machine deleted.')->with('active_tab', 'machines');
    }

    public function storeStaff(Request $request)
    {
        $request->validate(['name' => 'required', 'department' => 'required', 'role' => 'required']);
        Staff::create($request->all());
        return back()->with('success', 'Staff Member added.')->with('active_tab', 'staffs');
    }

    public function updateStaff(Request $request, Staff $staff)
    {
        $request->validate(['name' => 'required', 'department' => 'required', 'role' => 'required']);
        $staff->update($request->all());
        return back()->with('success', 'Staff Member updated.')->with('active_tab', 'staffs');
    }

    public function destroyStaff(Staff $staff)
    {
        $staff->delete();
        return back()->with('success', 'Staff Member deleted.')->with('active_tab', 'staffs');
    }
}
