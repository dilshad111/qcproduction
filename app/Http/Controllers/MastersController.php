<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ink;
use App\Models\CartonType;
use App\Models\MachineSpeed;
use App\Models\JobNumberSetup;
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
        
        return view('masters.index', compact('inks', 'cartonTypes', 'papers', 'machines', 'staffs', 'machineSpeed', 'jobNumberSetup'));
    }

    public function storeInk(Request $request)
    {
        $request->validate(['color_name' => 'required', 'color_code' => 'nullable']);
        Ink::create($request->all());
        return back()->with('success', 'Ink added successfully.');
    }

    public function destroyInk(Ink $ink)
    {
        $ink->delete();
        return back()->with('success', 'Ink deleted.');
    }

    public function storeCartonType(Request $request)
    {
        $request->validate(['name' => 'required']);
        CartonType::create($request->all());
        return back()->with('success', 'Carton Type added.');
    }

    public function destroyCartonType(CartonType $cartonType)
    {
        $cartonType->delete();
        return back()->with('success', 'Carton Type deleted.');
    }

    public function updateMachineSpeed(Request $request)
    {
        $machineSpeed = MachineSpeed::first();
        $machineSpeed->update($request->all());
        return back()->with('success', 'Machine Speed updated.');
    }

    public function storeJobNumberSetup(Request $request)
    {
        $request->validate([
            'job_number' => 'required|string'
        ]);

        $result = JobNumberSetup::parseAndSetup($request->job_number);
        
        if ($result) {
            return back()->with('success', 'Job Number Setup configured successfully.');
        } else {
            return back()->with('error', 'Invalid job number format. Please use format like "JC-QC-00001".');
        }
    }

    public function storePaper(Request $request)
    {
        $request->validate(['name' => 'required', 'gsm' => 'required|integer']);
        Paper::create($request->all());
        return back()->with('success', 'Paper added successfully.');
    }

    public function destroyPaper(Paper $paper)
    {
        $paper->delete();
        return back()->with('success', 'Paper deleted.');
    }

    public function storeMachine(Request $request)
    {
        $request->validate(['name' => 'required', 'type' => 'required']);
        Machine::create($request->all());
        return back()->with('success', 'Machine added.');
    }

    public function destroyMachine(Machine $machine)
    {
        $machine->delete();
        return back()->with('success', 'Machine deleted.');
    }

    public function storeStaff(Request $request)
    {
        $request->validate(['name' => 'required', 'role' => 'required']);
        Staff::create($request->all());
        return back()->with('success', 'Staff Member added.');
    }

    public function destroyStaff(Staff $staff)
    {
        $staff->delete();
        return back()->with('success', 'Staff Member deleted.');
    }
}
