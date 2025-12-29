<?php

namespace App\Http\Controllers;

use App\Models\JobCard;
use App\Models\JobCardLayer;
use App\Models\Customer;
use App\Models\CartonType;
use App\Models\Ink;
use App\Models\JobNumberSetup;
use App\Models\Paper;
use App\Services\DieLine;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDF;

class JobCardController extends Controller
{
    public function index(Request $request)
    {
        $query = JobCard::with('customer', 'cartonType')->where('is_active', true);
        
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
        $papers = Paper::all();
        return view('job_cards.create', compact('customers', 'cartonTypes', 'inks', 'papers'));
    }

    public function store(Request $request)
    {
        // Validation logic
        $validated = $request->validate([
            'customer_id' => 'required',
            'carton_type_id' => 'required',
            'item_name' => 'required',
            'pieces_count' => 'required|integer|min:1|max:5',
            'ply_type' => 'nullable|required_if:pieces_count,1|in:3,5,7',
            'pasting_type' => 'required|in:None,Glue,Staple',
        ]);

        DB::beginTransaction();
        try {
            // Generate Job No
            $jobNo = JobNumberSetup::getNextJobNumber();

            $jobCard = JobCard::create([
                'job_no' => $jobNo,
                'customer_id' => $request->customer_id,
                'carton_type_id' => $request->carton_type_id,
                'pieces_count' => $request->pieces_count,
                'item_name' => $request->item_name,
                'item_code' => $request->item_code,
                'uom' => $request->uom,
                'length' => $request->length,
                'width' => $request->width,
                'height' => $request->height,
                'deckle_size' => $request->pieces_count == 1 ? $request->deckle_size : null,
                'sheet_length' => $request->pieces_count == 1 ? $request->sheet_length : null,
                'ups' => $request->ups,
                'ply_type' => $request->pieces_count == 1 ? $request->ply_type : null,
                'slitting_creasing' => $request->slitting_creasing,
                'print_colors' => $request->print_colors,
                'printing_data' => $request->printing_data,
                'pasting_type' => $request->pasting_type,
                'staple_details' => $request->staple_details,
                'special_details' => $request->special_details,
                'process_type' => $request->process_type,
                'packing_bundle_qty' => $request->pieces_count == 1 ? $request->packing_bundle_qty : null,
                'packing_type' => $request->pieces_count == 1 ? $request->packing_type : null,
                'remarks' => $request->remarks,
                'corrugation_instruction' => $request->corrugation_instruction,
                'printing_instruction' => $request->printing_instruction,
                'finishing_instruction' => $request->finishing_instruction,
            ]);

            // Handle Multi-Piece or Single Piece
            if ($request->pieces_count > 1 && $request->has('pieces')) {
                // Multi-piece carton
                foreach ($request->pieces as $pieceNumber => $pieceData) {
                    $piece = \App\Models\JobCardPiece::create([
                        'job_card_id' => $jobCard->id,
                        'piece_number' => $pieceNumber + 1,
                        'piece_name' => $pieceData['piece_name'] ?? 'Piece ' . ($pieceNumber + 1),
                        'length' => $pieceData['length'] ?? null,
                        'width' => $pieceData['width'] ?? null,
                        'height' => $pieceData['height'] ?? null,
                        'deckle_size' => $pieceData['deckle_size'] ?? null,
                        'sheet_length' => $pieceData['sheet_length'] ?? null,
                        'ply_type' => $pieceData['ply_type'] ?? null,
                        'ups' => $pieceData['ups'] ?? 1,
                        'print_colors' => $pieceData['print_colors'] ?? 0,
                        'printing_data' => $pieceData['printing_data'] ?? null,
                        'packing_bundle_qty' => $pieceData['packing_bundle_qty'] ?? null,
                        'packing_type' => $pieceData['packing_type'] ?? null,
                        'slitting_creasing' => $pieceData['slitting_creasing'] ?? null,
                        'corrugation_instruction' => $pieceData['corrugation_instruction'] ?? null,
                        'printing_instruction' => $pieceData['printing_instruction'] ?? null,
                        'finishing_instruction' => $pieceData['finishing_instruction'] ?? null,
                        'die_sketch_path' => null,
                    ]);

                    // Save layers for this piece
                    if (isset($pieceData['layers']) && is_array($pieceData['layers'])) {
                        foreach ($pieceData['layers'] as $index => $layer) {
                            JobCardLayer::create([
                                'job_card_id' => $jobCard->id,
                                'piece_id' => $piece->id,
                                'layer_order' => $index + 1,
                                'type' => $layer['type'],
                                'paper_name' => $layer['paper_name'],
                                'gsm' => $layer['gsm'],
                                'flute_type' => $layer['flute_type'] ?? null,
                            ]);
                        }
                    }
                }
            } else {
                // Single piece carton (backward compatible)
                if ($request->layers) {
                    foreach ($request->layers as $index => $layer) {
                        JobCardLayer::create([
                            'job_card_id' => $jobCard->id,
                            'piece_id' => null,
                            'layer_order' => $index + 1,
                            'type' => $layer['type'],
                            'paper_name' => $layer['paper_name'],
                            'gsm' => $layer['gsm'],
                            'flute_type' => $layer['flute_type'] ?? null,
                        ]);
                    }
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
        $jobCard = JobCard::with(['customer', 'cartonType', 'layers', 'pieces.layers'])->findOrFail($id);
        return view('job_cards.show', compact('jobCard'));
    }

    public function edit($id)
    {
        $jobCard = JobCard::with(['layers', 'pieces.layers'])->findOrFail($id);
        $customers = Customer::all();
        $cartonTypes = CartonType::all();
        $inks = Ink::all();
        $papers = Paper::all();
        return view('job_cards.edit', compact('jobCard', 'customers', 'cartonTypes', 'inks', 'papers'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_id' => 'required',
            'carton_type_id' => 'required',
            'item_name' => 'required',
            'pieces_count' => 'required|integer|min:1|max:5',
            'ply_type' => 'nullable|required_if:pieces_count,1|in:3,5,7',
            'pasting_type' => 'required|in:None,Glue,Staple',
        ]);

        DB::beginTransaction();
        try {
            $jobCard = JobCard::findOrFail($id);
            
            $jobCard->update([
                'customer_id' => $request->customer_id,
                'carton_type_id' => $request->carton_type_id,
                'pieces_count' => $request->pieces_count,
                'item_name' => $request->item_name,
                'item_code' => $request->item_code,
                'uom' => $request->uom,
                'length' => $request->length,
                'width' => $request->width,
                'height' => $request->height,
                'deckle_size' => $request->pieces_count == 1 ? $request->deckle_size : null,
                'sheet_length' => $request->pieces_count == 1 ? $request->sheet_length : null,
                'ups' => $request->ups,
                'ply_type' => $request->pieces_count == 1 ? $request->ply_type : null,
                'slitting_creasing' => $request->slitting_creasing,
                'print_colors' => $request->print_colors,
                'printing_data' => $request->printing_data,
                'pasting_type' => $request->pasting_type,
                'staple_details' => $request->staple_details,
                'special_details' => $request->special_details,
                'process_type' => $request->process_type,
                'packing_bundle_qty' => $request->pieces_count == 1 ? $request->packing_bundle_qty : null,
                'packing_type' => $request->pieces_count == 1 ? $request->packing_type : null,
                'remarks' => $request->remarks,
                'corrugation_instruction' => $request->corrugation_instruction,
                'printing_instruction' => $request->printing_instruction,
                'finishing_instruction' => $request->finishing_instruction,
            ]);

            // Delete existing pieces and their layers
            $jobCard->pieces()->delete(); // Cascade will delete layers
            
            // Handle Multi-Piece or Single Piece
            if ($request->pieces_count > 1 && $request->has('pieces')) {
                // Multi-piece carton
                foreach ($request->pieces as $pieceNumber => $pieceData) {
                    $piece = \App\Models\JobCardPiece::create([
                        'job_card_id' => $jobCard->id,
                        'piece_number' => $pieceNumber + 1,
                        'piece_name' => $pieceData['piece_name'] ?? 'Piece ' . ($pieceNumber + 1),
                        'length' => $pieceData['length'] ?? null,
                        'width' => $pieceData['width'] ?? null,
                        'height' => $pieceData['height'] ?? null,
                        'deckle_size' => $pieceData['deckle_size'] ?? null,
                        'sheet_length' => $pieceData['sheet_length'] ?? null,
                        'ply_type' => $pieceData['ply_type'] ?? null,
                        'ups' => $pieceData['ups'] ?? 1,
                        'print_colors' => $pieceData['print_colors'] ?? 0,
                        'printing_data' => $pieceData['printing_data'] ?? null,
                        'packing_bundle_qty' => $pieceData['packing_bundle_qty'] ?? null,
                        'packing_type' => $pieceData['packing_type'] ?? null,
                        'slitting_creasing' => $pieceData['slitting_creasing'] ?? null,
                        'corrugation_instruction' => $pieceData['corrugation_instruction'] ?? null,
                        'printing_instruction' => $pieceData['printing_instruction'] ?? null,
                        'finishing_instruction' => $pieceData['finishing_instruction'] ?? null,
                        'die_sketch_path' => null,
                    ]);

                    // Save layers for this piece
                    if (isset($pieceData['layers']) && is_array($pieceData['layers'])) {
                        foreach ($pieceData['layers'] as $index => $layer) {
                            JobCardLayer::create([
                                'job_card_id' => $jobCard->id,
                                'piece_id' => $piece->id,
                                'layer_order' => $index + 1,
                                'type' => $layer['type'],
                                'paper_name' => $layer['paper_name'],
                                'gsm' => $layer['gsm'],
                                'flute_type' => $layer['flute_type'] ?? null,
                            ]);
                        }
                    }
                }
            } else {
                // Single piece carton - delete old layers and create new
                $jobCard->layers()->delete();
                if ($request->layers) {
                    foreach ($request->layers as $index => $layer) {
                        JobCardLayer::create([
                            'job_card_id' => $jobCard->id,
                            'piece_id' => null,
                            'layer_order' => $index + 1,
                            'type' => $layer['type'],
                            'paper_name' => $layer['paper_name'],
                            'gsm' => $layer['gsm'],
                            'flute_type' => $layer['flute_type'] ?? null,
                        ]);
                    }
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

    public function getByCustomer($id)
    {
        try {
            $jobs = JobCard::where('customer_id', $id)
                           ->where('is_active', true)
                           ->select('id', 'job_no', 'item_name', 'item_code')
                           ->orderBy('job_no', 'desc')
                           ->get();
            return response()->json($jobs);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function generateDieLine(Request $request)
    {
        $request->validate([
            'length' => 'required|numeric|min:1',
            'width' => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
            'item_name' => 'nullable|string',
            'fefco_code' => 'nullable|string',
        ]);

        $dieLine = new DieLine();
        $svg = $dieLine->generateSVG(
            $request->length,
            $request->width,
            $request->height,
            $request->fefco_code ?? '0201',
            $request->item_name ?? ''
        );

        return response()->json([
            'success' => true,
            'svg' => $svg,
        ]);
    }
    public function print($id)
    {
        $jobCard = JobCard::with('customer', 'cartonType', 'layers', 'pieces.layers')->findOrFail($id);
        return view('job_cards.print', compact('jobCard'));
    }

    public function history($id)
    {
        $jobCard = JobCard::findOrFail($id);
        $history = JobCard::with('customer')
            ->where('job_no', $jobCard->job_no)
            ->orderBy('version', 'desc')
            ->get();
            
        return view('job_cards.history', compact('jobCard', 'history'));
    }

    public function revise($id)
    {
        $jobCard = JobCard::with(['layers', 'pieces.layers'])->findOrFail($id);
        $customers = Customer::all();
        $cartonTypes = CartonType::all();
        $inks = Ink::all();
        $papers = Paper::all();
        return view('job_cards.revise', compact('jobCard', 'customers', 'cartonTypes', 'inks', 'papers'));
    }

    public function storeRevision(Request $request, $id)
    {
        $oldJobCard = JobCard::findOrFail($id);
        
        $validated = $request->validate([
            'customer_id' => 'required',
            'carton_type_id' => 'required',
            'item_name' => 'required',
            'pieces_count' => 'required|integer|min:1|max:5',
            'ply_type' => 'nullable|required_if:pieces_count,1|in:3,5,7',
            'pasting_type' => 'required|in:None,Glue,Staple',
            'revision_note' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Mark old version as inactive
            JobCard::where('job_no', $oldJobCard->job_no)->update(['is_active' => false]);

            // Create new version
            $jobCard = JobCard::create([
                'job_no' => $oldJobCard->job_no,
                'version' => $oldJobCard->version + 1,
                'is_active' => true,
                'customer_id' => $request->customer_id,
                'carton_type_id' => $request->carton_type_id,
                'pieces_count' => $request->pieces_count,
                'item_name' => $request->item_name,
                'item_code' => $request->item_code,
                'uom' => $request->uom,
                'length' => $request->length,
                'width' => $request->width,
                'height' => $request->height,
                'deckle_size' => $request->pieces_count == 1 ? $request->deckle_size : null,
                'sheet_length' => $request->pieces_count == 1 ? $request->sheet_length : null,
                'ups' => $request->ups,
                'ply_type' => $request->pieces_count == 1 ? $request->ply_type : null,
                'slitting_creasing' => $request->slitting_creasing,
                'print_colors' => $request->print_colors,
                'printing_data' => $request->printing_data,
                'pasting_type' => $request->pasting_type,
                'staple_details' => $request->staple_details,
                'special_details' => $request->special_details,
                'process_type' => $request->process_type,
                'packing_bundle_qty' => $request->pieces_count == 1 ? $request->packing_bundle_qty : null,
                'packing_type' => $request->pieces_count == 1 ? $request->packing_type : null,
                'remarks' => $request->remarks,
                'corrugation_instruction' => $request->corrugation_instruction,
                'printing_instruction' => $request->printing_instruction,
                'finishing_instruction' => $request->finishing_instruction,
                'revision_note' => $request->revision_note,
            ]);

            // Handle Multi-Piece or Single Piece
            if ($request->pieces_count > 1 && $request->has('pieces')) {
                foreach ($request->pieces as $pieceNumber => $pieceData) {
                    $piece = \App\Models\JobCardPiece::create([
                        'job_card_id' => $jobCard->id,
                        'piece_number' => $pieceNumber + 1,
                        'piece_name' => $pieceData['piece_name'] ?? 'Piece ' . ($pieceNumber + 1),
                        'length' => $pieceData['length'] ?? null,
                        'width' => $pieceData['width'] ?? null,
                        'height' => $pieceData['height'] ?? null,
                        'deckle_size' => $pieceData['deckle_size'] ?? null,
                        'sheet_length' => $pieceData['sheet_length'] ?? null,
                        'ply_type' => $pieceData['ply_type'] ?? null,
                        'ups' => $pieceData['ups'] ?? 1,
                        'print_colors' => $pieceData['print_colors'] ?? 0,
                        'printing_data' => $pieceData['printing_data'] ?? null,
                        'packing_bundle_qty' => $pieceData['packing_bundle_qty'] ?? null,
                        'packing_type' => $pieceData['packing_type'] ?? null,
                        'slitting_creasing' => $pieceData['slitting_creasing'] ?? null,
                        'corrugation_instruction' => $pieceData['corrugation_instruction'] ?? null,
                        'printing_instruction' => $pieceData['printing_instruction'] ?? null,
                        'finishing_instruction' => $pieceData['finishing_instruction'] ?? null,
                        'die_sketch_path' => null,
                    ]);

                    if (isset($pieceData['layers']) && is_array($pieceData['layers'])) {
                        foreach ($pieceData['layers'] as $index => $layer) {
                            JobCardLayer::create([
                                'job_card_id' => $jobCard->id,
                                'piece_id' => $piece->id,
                                'layer_order' => $index + 1,
                                'type' => $layer['type'],
                                'paper_name' => $layer['paper_name'],
                                'gsm' => $layer['gsm'],
                                'flute_type' => $layer['flute_type'] ?? null,
                            ]);
                        }
                    }
                }
            } else {
                if ($request->layers) {
                    foreach ($request->layers as $index => $layer) {
                        JobCardLayer::create([
                            'job_card_id' => $jobCard->id,
                            'piece_id' => null,
                            'layer_order' => $index + 1,
                            'type' => $layer['type'],
                            'paper_name' => $layer['paper_name'],
                            'gsm' => $layer['gsm'],
                            'flute_type' => $layer['flute_type'] ?? null,
                        ]);
                    }
                }
            }
            
            DB::commit();
            return redirect()->route('job-cards.index')->with('success', 'Job Card ' . $jobCard->job_no . ' revised to version ' . $jobCard->version);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error revising Job Card: ' . $e->getMessage())->withInput();
        }
    }
}
