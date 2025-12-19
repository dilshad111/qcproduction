<?php

namespace App\Http\Controllers;

use App\Models\JobCard;
use App\Models\JobCardLayer;
use App\Models\Customer;
use App\Models\CartonType;
use App\Models\Ink;
use App\Models\JobNumberSetup;
use App\Services\DieLine;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class JobCardController extends Controller
{
    public function index(Request $request)
    {
        $query = JobCard::with('customer', 'cartonType');
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('job_no', 'like', '%' . $search . '%')
                  ->orWhere('item_name', 'like', '%' . $search . '%')
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Customer filter
        if ($request->has('customer_id') && $request->customer_id != '') {
            $query->where('customer_id', $request->customer_id);
        }
        
        $jobCards = $query->latest()->get();
        $customers = Customer::all();
        
        return view('job_cards.index', compact('jobCards', 'customers'));
    }

    public function create()
    {
        $customers = Customer::all();
        $cartonTypes = CartonType::all();
        $inks = Ink::all();
        return view('job_cards.create', compact('customers', 'cartonTypes', 'inks'));
    }

    public function store(Request $request)
    {
        // Validation logic here (simplified for brevity, should be robust)
        $validated = $request->validate([
            'customer_id' => 'required',
            'carton_type_id' => 'required',
            'item_name' => 'required',
            'ply_type' => 'required|in:3,5,7',
        ]);

        DB::beginTransaction();
        try {
            // Generate Job No using auto-increment
            $jobNo = JobNumberSetup::getNextJobNumber();

            $jobCard = JobCard::create([
                'job_no' => $jobNo,
                'customer_id' => $request->customer_id,
                'carton_type_id' => $request->carton_type_id,
                'item_name' => $request->item_name,
                'item_code' => $request->item_code,
                'length' => $request->length,
                'width' => $request->width,
                'height' => $request->height,
                'deckle_size' => $request->deckle_size,
                'sheet_length' => $request->sheet_length,
                'ups' => $request->ups,
                'ply_type' => $request->ply_type,
                'slitting_creasing' => $request->slitting_creasing,
                'print_colors' => $request->print_colors,
                'printing_data' => $request->printing_data, // JSON
                'pasting_type' => $request->pasting_type,
                'staple_details' => $request->staple_details,
                'special_details' => $request->special_details, // JSON
                'process_type' => $request->process_type,
                'packing_bundle_qty' => $request->packing_bundle_qty,
                'remarks' => $request->remarks,
            ]);

            // Save Layers
            if($request->layers) {
                foreach($request->layers as $index => $layer) {
                    JobCardLayer::create([
                        'job_card_id' => $jobCard->id,
                        'layer_order' => $index + 1,
                        'type' => $layer['type'],
                        'paper_name' => $layer['paper_name'],
                        'gsm' => $layer['gsm'],
                        'flute_type' => $layer['flute_type'] ?? null,
                    ]);
                }
            }
            
            DB::commit();
            return redirect()->route('job-cards.index')->with('success', 'Job Card created: ' . $jobNo);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error creating Job Card: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $jobCard = JobCard::with('customer', 'cartonType', 'layers')->findOrFail($id);
        return view('job_cards.show', compact('jobCard'));
    }

    public function edit($id)
    {
        $jobCard = JobCard::with('layers')->findOrFail($id);
        $customers = Customer::all();
        $cartonTypes = CartonType::all();
        $inks = Ink::all();
        return view('job_cards.edit', compact('jobCard', 'customers', 'cartonTypes', 'inks'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_id' => 'required',
            'carton_type_id' => 'required',
            'item_name' => 'required',
            'ply_type' => 'required|in:3,5,7',
        ]);

        DB::beginTransaction();
        try {
            $jobCard = JobCard::findOrFail($id);
            
            $jobCard->update([
                'customer_id' => $request->customer_id,
                'carton_type_id' => $request->carton_type_id,
                'item_name' => $request->item_name,
                'item_code' => $request->item_code,
                'length' => $request->length,
                'width' => $request->width,
                'height' => $request->height,
                'deckle_size' => $request->deckle_size,
                'sheet_length' => $request->sheet_length,
                'ups' => $request->ups,
                'ply_type' => $request->ply_type,
                'slitting_creasing' => $request->slitting_creasing,
                'print_colors' => $request->print_colors,
                'printing_data' => $request->printing_data,
                'pasting_type' => $request->pasting_type,
                'staple_details' => $request->staple_details,
                'special_details' => $request->special_details,
                'process_type' => $request->process_type,
                'packing_bundle_qty' => $request->packing_bundle_qty,
                'remarks' => $request->remarks,
            ]);

            // Update Layers
            $jobCard->layers()->delete();
            if($request->layers) {
                foreach($request->layers as $index => $layer) {
                    JobCardLayer::create([
                        'job_card_id' => $jobCard->id,
                        'layer_order' => $index + 1,
                        'type' => $layer['type'],
                        'paper_name' => $layer['paper_name'],
                        'gsm' => $layer['gsm'],
                        'flute_type' => $layer['flute_type'] ?? null,
                    ]);
                }
            }
            
            DB::commit();
            return redirect()->route('job-cards.index')->with('success', 'Job Card updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error updating Job Card: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $jobCard = JobCard::findOrFail($id);
            $jobNo = $jobCard->job_no;
            $jobCard->delete();
            return redirect()->route('job-cards.index')->with('success', 'Job Card ' . $jobNo . ' deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting Job Card: ' . $e->getMessage());
        }
    }

    public function getByCustomer(Customer $customer)
    {
        $jobs = JobCard::where('customer_id', $customer->id)->select('id', 'job_no', 'item_name', 'item_code')->get();
        return response()->json($jobs);
    }

    public function generateDieLine(Request $request)
    {
        $request->validate([
            'length' => 'required|numeric|min:1',
            'width' => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
            'fefco_code' => 'nullable|string',
        ]);

        $dieLine = new DieLine();
        $svg = $dieLine->generateSVG(
            $request->length,
            $request->width,
            $request->height,
            $request->fefco_code ?? '0201'
        );

        return response()->json([
            'success' => true,
            'svg' => $svg,
        ]);
    }
    public function print($id)
    {
        $jobCard = JobCard::with('customer', 'cartonType', 'layers')->findOrFail($id);
        return view('job_cards.print', compact('jobCard'));
    }
}
