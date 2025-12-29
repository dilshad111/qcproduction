@extends('layouts.app')

@section('content')
<style>
    /* Modern UI Theme - Darker fonts for better visibility */
    
    /* Typography */
    label, .form-label {
        color: #000000 !important;
        font-weight: 600 !important;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        color: #000000 !important;
        font-weight: 500 !important;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 0.6rem 0.75rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.15);
        outline: none;
    }
    
    .form-control::placeholder {
        color: #666666 !important;
        font-weight: 400 !important;
    }
    
    /* Cards */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
        transition: box-shadow 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }
    
    .card-header {
        color: #000000 !important;
        font-weight: 700 !important;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white !important;
        border-radius: 12px 12px 0 0 !important;
        padding: 1rem 1.25rem;
        border: none;
    }
    
    /* Headings */
    h2, h3, h4, h5, h6 {
        color: #000000 !important;
        font-weight: 700 !important;
    }
    
    h2 {
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 3px solid #667eea;
    }
    
    /* Modern Buttons */
    .btn {
        border-radius: 8px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    .btn:active {
        transform: translateY(0);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #5568d3 0%, #63408a 100%);
    }
    
    .btn-success {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        border: none;
    }
    
    .btn-success:hover {
        background: linear-gradient(135deg, #45a049 0%, #3d8b40 100%);
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
        border: none;
    }
    
    .btn-danger:hover {
        background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%);
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #757575 0%, #616161 100%);
        border: none;
    }
    
    .btn-secondary:hover {
        background: linear-gradient(135deg, #616161 0%, #424242 100%);
    }
    
    .btn-info {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        border: none;
    }
    
    .btn-info:hover {
        background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
    }
    
    /* Form Check (Checkboxes) */
    .form-check-input {
        width: 1.25rem;
        height: 1.25rem;
        border: 2px solid #667eea;
        border-radius: 4px;
        cursor: pointer;
    }
    
    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    
    .form-check-label {
        margin-left: 0.5rem;
        cursor: pointer;
        font-weight: 600;
    }
    
    /* Alerts */
    .alert {
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    /* Container */
    .container {
        padding-top: 2rem;
        padding-bottom: 2rem;
    }
    
    /* Input Groups */
    .input-group {
        border-radius: 8px;
        overflow: hidden;
    }
    
    /* Smooth Animations */
    * {
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }
</style>
<div class="container">
    <h2>Revise Job Card: {{ $jobCard->customer->name }} - {{ $jobCard->item_name }}</h2>
    <p class="text-muted">Job Card No: {{ $jobCard->job_no }} (Current Version: {{ $jobCard->version }})</p>
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('job-cards.revise.store', $jobCard->id) }}" method="POST" id="jobCardForm">
        @csrf
        
        <!-- REVISION NOTE -->
        <div class="card mb-3 border-warning border-2">
            <div class="card-header bg-warning">Revision Details</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-dark">Reason for Revision / Change Request Details <span class="text-danger">*</span></label>
                    <textarea name="revision_note" class="form-control" rows="3" placeholder="Explain what changed (e.g., Size increased by 5mm as per customer request, changed paper grade...)" required></textarea>
                    <small class="text-muted">This note will be saved in the version history.</small>
                </div>
            </div>
        </div>
        
        <!-- BASIC INFO -->
        <div class="card mb-3">
            <div class="card-header">Basic Information</div>
            <div class="card-body">
                <div class="row">
                    <!-- Left Columns (9 columns) -->
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Customer</label>
                                <select name="customer_id" class="form-control" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" {{ old('customer_id', $jobCard->customer_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Carton Type</label>
                                <select name="carton_type_id" id="carton_type_select" class="form-control" onchange="updateCartonPreview()" required>
                                    <option value="">Select Type</option>
                                    @foreach($cartonTypes as $t)
                                        <option value="{{ $t->id }}" data-code="{{ $t->standard_code }}" {{ old('carton_type_id', $jobCard->carton_type_id) == $t->id ? 'selected' : '' }}>{{ $t->name }} ({{ $t->standard_code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Item Name</label>
                                <input type="text" name="item_name" class="form-control" value="{{ old('item_name', $jobCard->item_name) }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Item Code</label>
                                <input type="text" name="item_code" class="form-control" value="{{ old('item_code', $jobCard->item_code) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Right Column (Preview) -->
                    <div class="col-md-3 d-flex align-items-center justify-content-center border-start">
                        <div id="carton_preview_container" class="text-center" style="display:none; width: 100%;">
                            <label class="small text-muted d-block mb-2">Design Preview</label>
                            <img id="carton_preview_img" src="" style="max-width: 100%; max-height: 200px; object-fit: contain;" 
                                 onerror="if(this.src.endsWith('.png')){this.src=this.src.replace('.png','.jpg')}else{this.parentElement.style.display='none'}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Scale (UOM)</label>
                        <select name="uom" id="uom" class="form-control" onchange="updateCalculations()">
                            <option value="mm" {{ old('uom', $jobCard->uom) == 'mm' ? 'selected' : '' }}>MM</option>
                            <option value="inch" {{ old('uom', $jobCard->uom) == 'inch' ? 'selected' : '' }}>Inch</option>
                            <option value="cm" {{ old('uom', $jobCard->uom) == 'cm' ? 'selected' : '' }}>CM</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Inner Length (mm)</label>
                        <input type="number" step="0.01" name="length" id="dimension_length" class="form-control" value="{{ old('length', $jobCard->length) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Inner Width (mm)</label>
                        <input type="number" step="0.01" name="width" id="dimension_width" class="form-control" value="{{ old('width', $jobCard->width) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Inner Height (mm)</label>
                        <input type="number" step="0.01" name="height" id="dimension_height" class="form-control" value="{{ old('height', $jobCard->height) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-info btn-block" onclick="generateDieLine()">
                            📐 Preview Die-Line
                        </button>
                    </div>
                </div>
                
                <!-- PIECES COUNT SELECTOR -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Number of Pieces</label>
                        <select name="pieces_count" id="pieces_count" class="form-control" required onchange="togglePieceMode()">
                            <option value="1" {{ old('pieces_count', $jobCard->pieces_count) == 1 ? 'selected' : '' }}>1 Piece (Standard)</option>
                            <option value="2" {{ old('pieces_count', $jobCard->pieces_count) == 2 ? 'selected' : '' }}>2 Pieces (e.g., Lid & Base)</option>
                            <option value="3" {{ old('pieces_count', $jobCard->pieces_count) == 3 ? 'selected' : '' }}>3 Pieces</option>
                            <option value="4" {{ old('pieces_count', $jobCard->pieces_count) == 4 ? 'selected' : '' }}>4 Pieces</option>
                            <option value="5" {{ old('pieces_count', $jobCard->pieces_count) == 5 ? 'selected' : '' }}>5 Pieces</option>
                        </select>
                        <small class="text-muted">Select number of separate pieces for this carton</small>
                    </div>
                </div>

                <!-- SINGLE PIECE FIELDS (Hidden when multi-piece) -->
                <div id="single_piece_fields" style="display: {{ $jobCard->pieces_count > 1 ? 'none' : 'block' }};">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Deckle Size Inch (Calc)</label>
                            <input type="number" step="0.01" id="deckle_size_calc" class="form-control" readonly style="background-color: #e9ecef;">
                            <small class="text-muted">(W+H)+25</small>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Sheet Length Inch (Calc)</label>
                            <input type="number" step="0.01" id="sheet_length_calc" class="form-control" readonly style="background-color: #e9ecef;">
                            <small class="text-muted">(L+L+W+W+75)</small>
                        </div>
                    </div>

                    <div class="row">
                       <div class="col-md-4 mb-3">
                            <label>Deckle Size (Inch)</label>
                            <input type="number" step="0.01" name="deckle_size" class="form-control" value="{{ old('deckle_size', $jobCard->deckle_size) }}">
                       </div>
                       <div class="col-md-4 mb-3">
                            <label>Sheet Length (Inch)</label>
                            <input type="number" step="0.01" name="sheet_length" class="form-control" value="{{ old('sheet_length', $jobCard->sheet_length) }}">
                       </div>
                       <div class="col-md-4 mb-3">
                            <label>UPS (Cartons/Sheet)</label>
                            <input type="number" name="ups" class="form-control" value="{{ old('ups', $jobCard->ups) }}">
                       </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Ply Type</label>
                            <select name="ply_type" id="ply_type" class="form-control" onchange="renderLayers(true)">
                                <option value="">Select Ply</option>
                                <option value="3" {{ old('ply_type', $jobCard->ply_type) == '3' ? 'selected' : '' }}>3-Ply</option>
                                <option value="5" {{ old('ply_type', $jobCard->ply_type) == '5' ? 'selected' : '' }}>5-Ply</option>
                                <option value="7" {{ old('ply_type', $jobCard->ply_type) == '7' ? 'selected' : '' }}>7-Ply</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Slitting Creasing</label>
                            <select name="slitting_creasing" class="form-control">
                                <option value="">Select Option</option>
                                <option value="Plant Online" {{ old('slitting_creasing', $jobCard->slitting_creasing) == 'Plant Online' ? 'selected' : '' }}>Plant Online</option>
                                <option value="Manual" {{ old('slitting_creasing', $jobCard->slitting_creasing) == 'Manual' ? 'selected' : '' }}>Manual</option>
                                <option value="Die Cutting" {{ old('slitting_creasing', $jobCard->slitting_creasing) == 'Die Cutting' ? 'selected' : '' }}>Die Cutting</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>Corrugation Special Instruction</label>
                            <textarea name="corrugation_instruction" class="form-control" rows="2" placeholder="Instructions for Corrugation Plant...">{{ old('corrugation_instruction', $jobCard->corrugation_instruction) }}</textarea>
                        </div>
                    </div>

                    <!-- PAPER STRUCTURE LOGIC -->
                    <div class="card mb-3" id="paper-structure-card" style="display:none;">
                        <div class="card-header">Paper Structure Configuration</div>
                        <div class="card-body" id="layers-container">
                            <!-- Javascript will render rows here -->
                        </div>
                    </div>
                </div>

                <!-- MULTI PIECE CONTAINER -->
                <div id="multi_piece_container" style="display: {{ $jobCard->pieces_count > 1 ? 'block' : 'none' }};">
                    <hr>
                    <h4 class="mb-3">Multi-Piece Configuration</h4>
                    
                    <ul class="nav nav-tabs" id="pieceTabs" role="tablist">
                        <!-- Tabs will be generated by JS -->
                    </ul>
                    <div class="tab-content border-left border-right border-bottom p-3" id="pieceTabContent">
                        <!-- Tab contents will be generated by JS -->
                    </div>
                </div>

        <!-- PRINTING CONFIG (Hidden for multi-piece) -->
        <div class="card mb-3" id="printing-config-card" style="display: {{ $jobCard->pieces_count > 1 ? 'none' : 'block' }};">
            <div class="card-header">Printing Configuration</div>
            <div class="card-body">
                <div class="mb-3">
                    <label>No. of Printing Colors</label>
                    <select name="print_colors" id="print_colors" class="form-control" onchange="renderInkFields()">
                        <option value="0" {{ old('print_colors', $jobCard->print_colors) == '0' ? 'selected' : '' }}>Un-Printed</option>
                        <option value="1" {{ old('print_colors', $jobCard->print_colors) == '1' ? 'selected' : '' }}>1-Color</option>
                        <option value="2" {{ old('print_colors', $jobCard->print_colors) == '2' ? 'selected' : '' }}>2-Color</option>
                        <option value="3" {{ old('print_colors', $jobCard->print_colors) == '3' ? 'selected' : '' }}>3-Color</option>
                        <option value="4" {{ old('print_colors', $jobCard->print_colors) == '4' ? 'selected' : '' }}>4-Color</option>
                        <option value="5" {{ old('print_colors', $jobCard->print_colors) == '5' ? 'selected' : '' }}>5-Color</option>
                    </select>
                </div>
                <div id="ink-fields-container" class="row"></div>
                <div class="row mt-3">
                    <div class="col-md-12 mb-3">
                        <label>Printing Special Instruction</label>
                        <textarea name="printing_instruction" class="form-control" rows="2" placeholder="Instructions for Printing Department...">{{ old('printing_instruction', $jobCard->printing_instruction) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- PASTING & PROCESS -->
        <div class="card mb-3">
            <div class="card-header">Finishing & Process</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Pasting Option</label>
                        <select name="pasting_type" class="form-control" onchange="toggleStaple(this.value)">
                            <option value="None" {{ old('pasting_type', $jobCard->pasting_type) == 'None' ? 'selected' : '' }}>No Pasting</option>
                            <option value="Glue" {{ old('pasting_type', $jobCard->pasting_type) == 'Glue' ? 'selected' : '' }}>Glue</option>
                            <option value="Staple" {{ old('pasting_type', $jobCard->pasting_type) == 'Staple' ? 'selected' : '' }}>Stapling</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3" id="staple_div" style="display:none;">
                        <label>Staple Details</label>
                        <input type="text" name="staple_details" class="form-control" value="{{ old('staple_details', $jobCard->staple_details) }}" placeholder="No. of Pins">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Process Type</label>
                        <select name="process_type" class="form-control">
                            <option value="Rotary Slotter" {{ old('process_type', $jobCard->process_type) == 'Rotary Slotter' ? 'selected' : '' }}>Rotary Slotter</option>
                            <option value="Die Cutting" {{ old('process_type', $jobCard->process_type) == 'Die Cutting' ? 'selected' : '' }}>Die Cutting</option>
                        </select>
                    </div>
                </div>
                <!-- Special Cases -->
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_honeycomb" onchange="toggleSpecial('honeycomb')" {{ isset($jobCard->special_details['honeycomb']) ? 'checked' : '' }}>
                    <label class="form-check-label">Honeycomb Add-on</label>
                </div>
                <div id="honeycomb_fields" class="mb-3 p-3 border bg-light" style="display:none;">
                    <h5 class="mb-3">Honeycomb Specifications</h5>
                    @php $h = $jobCard->special_details['honeycomb'] ?? null; @endphp
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Honeycomb Holes</label>
                            <input type="number" name="special_details[honeycomb][holes]" class="form-control" value="{{ $h['holes'] ?? '' }}" placeholder="e.g., 100">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Plies</label>
                            <select name="special_details[honeycomb][plies]" class="form-control">
                                <option value="">Select Plies</option>
                                <option value="3" {{ ($h['plies'] ?? '') == '3' ? 'selected' : '' }}>3-Ply</option>
                                <option value="5" {{ ($h['plies'] ?? '') == '5' ? 'selected' : '' }}>5-Ply</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>UOM</label>
                            <select name="special_details[honeycomb][uom]" class="form-control">
                                <option value="mm" {{ ($h['uom'] ?? '') == 'mm' ? 'selected' : '' }}>MM</option>
                                <option value="inch" {{ ($h['uom'] ?? '') == 'inch' ? 'selected' : '' }}>Inch</option>
                                <option value="cm" {{ ($h['uom'] ?? '') == 'cm' ? 'selected' : '' }}>CM</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Length</label>
                            <input type="number" step="0.01" name="special_details[honeycomb][length]" class="form-control" value="{{ $h['length'] ?? '' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Width</label>
                            <input type="number" step="0.01" name="special_details[honeycomb][width]" class="form-control" value="{{ $h['width'] ?? '' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Height</label>
                            <input type="number" step="0.01" name="special_details[honeycomb][height]" class="form-control" value="{{ $h['height'] ?? '' }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Hole Size (L x W)</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" step="0.01" name="special_details[honeycomb][hole_length]" class="form-control" value="{{ $h['hole_length'] ?? '' }}" placeholder="Length">
                                </div>
                                <div class="col-6">
                                    <input type="number" step="0.01" name="special_details[honeycomb][hole_width]" class="form-control" value="{{ $h['hole_width'] ?? '' }}" placeholder="Width">
                                </div>
                            </div>
                            <small class="text-muted">Enter the dimensions of each honeycomb hole</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Material (Paper Construction)</label>
                            <input type="text" name="special_details[honeycomb][material]" class="form-control" value="{{ $h['material'] ?? '' }}" placeholder="e.g., Kraft Paper">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Source</label>
                            <select name="special_details[honeycomb][source]" class="form-control" onchange="toggleHoneycombSupplier(this.value)">
                                <option value="">Select Source</option>
                                <option value="in_house" {{ ($h['source'] ?? '') == 'in_house' ? 'selected' : '' }}>Made In-House</option>
                                <option value="outsource" {{ ($h['source'] ?? '') == 'outsource' ? 'selected' : '' }}>Outsource</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" id="honeycomb_supplier_row" style="display:{{ ($h['source'] ?? '') == 'outsource' ? 'block' : 'none' }};">
                        <div class="col-md-12 mb-3">
                            <label>Supplier Name</label>
                            <input type="text" name="special_details[honeycomb][supplier_name]" class="form-control" value="{{ $h['supplier_name'] ?? '' }}" placeholder="Enter supplier name">
                        </div>
                    </div>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_separator" onchange="toggleSpecial('separator')" {{ isset($jobCard->special_details['separator']) ? 'checked' : '' }}>
                    <label class="form-check-label">Separator Add-on</label>
                </div>
                <div id="separator_fields" class="mb-3 p-3 border bg-light" style="display:none;">
                     <h5 class="mb-3">Separator Specifications</h5>
                     @php $s = $jobCard->special_details['separator'] ?? null; @endphp
                     <div class="row">
                         <div class="col-md-4 mb-3">
                             <label>Plies</label>
                             <select name="special_details[separator][plies]" class="form-control">
                                 <option value="">Select Plies</option>
                                 <option value="2" {{ ($s['plies'] ?? '') == '2' ? 'selected' : '' }}>2-Ply</option>
                                 <option value="3" {{ ($s['plies'] ?? '') == '3' ? 'selected' : '' }}>3-Ply</option>
                                 <option value="4" {{ ($s['plies'] ?? '') == '4' ? 'selected' : '' }}>4-Ply</option>
                                 <option value="5" {{ ($s['plies'] ?? '') == '5' ? 'selected' : '' }}>5-Ply</option>
                             </select>
                         </div>
                         <div class="col-md-4 mb-3">
                             <label>UOM</label>
                             <select name="special_details[separator][uom]" class="form-control">
                                 <option value="mm" {{ ($s['uom'] ?? '') == 'mm' ? 'selected' : '' }}>MM</option>
                                 <option value="inch" {{ ($s['uom'] ?? '') == 'inch' ? 'selected' : '' }}>Inch</option>
                                 <option value="cm" {{ ($s['uom'] ?? '') == 'cm' ? 'selected' : '' }}>CM</option>
                             </select>
                         </div>
                     </div>
                     <div class="row">
                         <div class="col-md-6 mb-3">
                             <label>Length</label>
                             <input type="number" step="0.01" name="special_details[separator][length]" class="form-control" value="{{ $s['length'] ?? '' }}">
                         </div>
                         <div class="col-md-6 mb-3">
                             <label>Width</label>
                             <input type="number" step="0.01" name="special_details[separator][width]" class="form-control" value="{{ $s['width'] ?? '' }}">
                         </div>
                     </div>
                     <div class="row">
                         <div class="col-md-6 mb-3">
                             <label>Material (Paper Construction)</label>
                             <input type="text" name="special_details[separator][material]" class="form-control" value="{{ $s['material'] ?? '' }}" placeholder="e.g., Kraft Paper">
                         </div>
                         <div class="col-md-6 mb-3">
                             <label>Source</label>
                             <select name="special_details[separator][source]" class="form-control" onchange="toggleSeparatorSupplier(this.value)">
                                 <option value="">Select Source</option>
                                 <option value="in_house" {{ ($s['source'] ?? '') == 'in_house' ? 'selected' : '' }}>Made In-House</option>
                                 <option value="outsource" {{ ($s['source'] ?? '') == 'outsource' ? 'selected' : '' }}>Outsource</option>
                             </select>
                         </div>
                     </div>
                     <div class="row" id="separator_supplier_row" style="display:{{ ($s['source'] ?? '') == 'outsource' ? 'block' : 'none' }};">
                         <div class="col-md-12 mb-3">
                             <label>Supplier Name</label>
                             <input type="text" name="special_details[separator][supplier_name]" class="form-control" value="{{ $s['supplier_name'] ?? '' }}" placeholder="Enter supplier name">
                         </div>
                     </div>
                </div>

                 <div class="row mt-3" id="global_finishing_instruction_row">
                    <div class="col-md-12 mb-3">
                        <label>Finishing Special Instruction</label>
                        <textarea name="finishing_instruction" class="form-control" rows="2" placeholder="Instructions for Finishing/Pasting...">{{ old('finishing_instruction', $jobCard->finishing_instruction) }}</textarea>
                    </div>
                </div>

                <div id="main_packing_div" style="display: {{ $jobCard->pieces_count > 1 ? 'none' : 'block' }};">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Packing Qty (Cartons per Bundle)</label>
                            <input type="number" name="packing_bundle_qty" class="form-control" value="{{ old('packing_bundle_qty', $jobCard->packing_bundle_qty) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Packing Type</label>
                            <select name="packing_type" class="form-control">
                                <option value="">Select Packing Type</option>
                                <option value="Strapping" {{ old('packing_type', $jobCard->packing_type) == 'Strapping' ? 'selected' : '' }}>Strapping</option>
                                <option value="Packed in Box" {{ old('packing_type', $jobCard->packing_type) == 'Packed in Box' ? 'selected' : '' }}>Packed in Box</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Remarks</label>
                    <textarea name="remarks" class="form-control">{{ old('remarks', $jobCard->remarks) }}</textarea>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg">Save New Version (Revise)</button>
    </form>

    <!-- Die-Line Preview Modal -->
    <div class="modal fade" id="dieLineModal" tabindex="-1" aria-labelledby="dieLineModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dieLineModalLabel">📐 Die-Line Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="dieline-loading" style="display:none;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p>Generating die-line...</p>
                    </div>
                    <div id="dieline-container"></div>
                    <canvas id="dieline-canvas" style="display:none;"></canvas>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="downloadJPEG()">
                        📥 Download JPEG
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const inks = @json($inks);
    const papers = @json($papers);
    const existingLayers = @json($jobCard->layers);
    const existingPrintingData = @json($jobCard->printing_data);
    const existingPieces = @json($jobCard->pieces);

    // Update Carton Preview
    function updateCartonPreview() {
        const select = document.getElementById('carton_type_select');
        const container = document.getElementById('carton_preview_container');
        const img = document.getElementById('carton_preview_img');
        
        if (!select || !select.value) {
            if(container) container.style.display = 'none';
            return;
        }

        const selectedOption = select.options[select.selectedIndex];
        if (!selectedOption) return;

        let code = selectedOption.getAttribute('data-code');
        if (!code) {
            const match = selectedOption.text.match(/\(([^)]+)\)/);
            if (match) code = match[1];
        }
        
        if (code) {
            code = code.trim();
            // Reset error handler state by setting src
            img.style.display = 'inline-block';
            container.style.display = 'block';
            img.src = `/images/fefco/${code}.png`;
        } else {
            container.style.display = 'none';
        }
    }

    // Calculate Deckle Size and Sheet Length
    function updateCalculations() {
        const length = parseFloat(document.getElementById('dimension_length').value) || 0;
        const width = parseFloat(document.getElementById('dimension_width').value) || 0;
        const height = parseFloat(document.getElementById('dimension_height').value) || 0;
        const uom = document.getElementById('uom').value;

        let l_mm = length;
        let w_mm = width;
        let h_mm = height;

        if (uom === 'inch') {
            l_mm = length * 25.4;
            w_mm = width * 25.4;
            h_mm = height * 25.4;
        } else if (uom === 'cm') {
            l_mm = length * 10;
            w_mm = width * 10;
            h_mm = height * 10;
        }

        const deckle_mm = (w_mm + h_mm) + 25;
        const sheet_length_mm = (l_mm * 2) + (w_mm * 2) + 75;

        const deckle_inch = deckle_mm / 25.4;
        const sheet_length_inch = sheet_length_mm / 25.4;

        if (document.getElementById('deckle_size_calc')) {
            document.getElementById('deckle_size_calc').value = deckle_inch.toFixed(2);
        }
        if (document.getElementById('sheet_length_calc')) {
            document.getElementById('sheet_length_calc').value = sheet_length_inch.toFixed(2);
        }
    }

    document.getElementById('dimension_length').addEventListener('input', updateCalculations);
    document.getElementById('dimension_width').addEventListener('input', updateCalculations);
    document.getElementById('dimension_height').addEventListener('input', updateCalculations);
    
    updateCalculations();

    // Render Layers (Single Piece)
    function renderLayers(reset = false) {
        const ply = document.getElementById('ply_type').value;
        const container = document.getElementById('layers-container');
        const card = document.getElementById('paper-structure-card');
        
        container.innerHTML = '';
        if(!ply) {
            card.style.display = 'none';
            return;
        }
        card.style.display = 'block';

        let structure = [];
        if(ply === '3') {
            structure = [
                { label: 'Top Liner', type: 'Top Layer' },
                { label: 'Fluting', type: 'Flute Layer', hasFluteType: true },
                { label: 'Back Liner', type: 'Back Layer' }
            ];
        } else if(ply === '5') {
            structure = [
                { label: 'Top Liner', type: 'Top Layer' },
                { label: 'Fluting 1', type: 'Flute Layer', hasFluteType: true },
                { label: 'Center Liner', type: 'Center Layer' },
                { label: 'Fluting 2', type: 'Flute Layer', hasFluteType: true },
                { label: 'Back Liner', type: 'Back Layer' }
            ];
        } else if(ply === '7') {
            structure = [
                { label: 'Top Liner', type: 'Top Layer' },
                { label: 'Fluting 1', type: 'Flute Layer', hasFluteType: true },
                { label: 'Center Liner 1', type: 'Center Layer' },
                { label: 'Fluting 2', type: 'Flute Layer', hasFluteType: true },
                { label: 'Center Liner 2', type: 'Center Layer' },
                { label: 'Fluting 3', type: 'Flute Layer', hasFluteType: true },
                { label: 'Back Liner', type: 'Back Layer' }
            ];
        }

        structure.forEach((layer, index) => {
            let paperName = '';
            let gsm = '';
            let fluteVal = '';

            if (!reset && existingLayers && existingLayers[index]) {
                paperName = existingLayers[index].paper_name;
                gsm = existingLayers[index].gsm;
                fluteVal = existingLayers[index].flute_type;
            }

            let paperOptions = '<option value="">Select Paper</option>';
            papers.forEach(p => {
                const selected = (paperName === p.name) ? 'selected' : '';
                paperOptions += `<option value="${p.name}" ${selected}>${p.name}</option>`;
            });

            let fluteHtml = layer.hasFluteType ? `
                <select name="layers[${index}][flute_type]" class="form-control form-control-sm">
                    <option value="">Flute</option>
                    <option value="A" ${fluteVal === 'A' ? 'selected' : ''}>A</option>
                    <option value="B" ${fluteVal === 'B' ? 'selected' : ''}>B</option>
                    <option value="C" ${fluteVal === 'C' ? 'selected' : ''}>C</option>
                    <option value="E" ${fluteVal === 'E' ? 'selected' : ''}>E</option>
                </select>
            ` : '<span class="form-control form-control-sm bg-light">-</span>';

            let html = `
                <div class="row mb-2 align-items-center">
                    <div class="col-md-3"><strong>${layer.label}</strong><input type="hidden" name="layers[${index}][type]" value="${layer.type}"></div>
                    <div class="col-md-4"><select name="layers[${index}][paper_name]" class="form-control form-control-sm" required>${paperOptions}</select></div>
                    <div class="col-md-2"><input type="number" name="layers[${index}][gsm]" class="form-control form-control-sm" value="${gsm}" placeholder="GSM" required></div>
                    <div class="col-md-3">${fluteHtml}</div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        });
    }

    // Render Ink Fields (Single Piece)
    function renderInkFields() {
        const count = document.getElementById('print_colors').value;
        const container = document.getElementById('ink-fields-container');
        container.innerHTML = '';
        
        let currentInks = (existingPrintingData && existingPrintingData.inks) ? existingPrintingData.inks : [];

        for(let i=0; i<count; i++) {
            let options = '<option value="">Select Ink</option>';
            inks.forEach(ink => {
                const selected = (currentInks[i] == ink.id) ? 'selected' : '';
                options += `<option value="${ink.id}" ${selected}>${ink.color_name} (${ink.color_code})</option>`;
            });

            let html = `
                <div class="col-md-4 mb-2">
                    <label>Ink Color ${i+1}</label>
                    <select name="printing_data[inks][]" class="form-control">${options}</select>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        }
    }

    function toggleStaple(val) {
        document.getElementById('staple_div').style.display = (val === 'Staple') ? 'block' : 'none';
    }

    function toggleSpecial(type) {
        const check = document.getElementById('check_' + type);
        const fields = document.getElementById(type + '_fields');
        if (fields) {
            fields.style.display = check.checked ? 'block' : 'none';
            const inputs = fields.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.disabled = !check.checked;
            });
        }
    }

    function toggleHoneycombSupplier(source) {
        const row = document.getElementById('honeycomb_supplier_row');
        if (row) row.style.display = (source === 'outsource') ? 'block' : 'none';
    }

    function toggleSeparatorSupplier(source) {
        const row = document.getElementById('separator_supplier_row');
        if (row) row.style.display = (source === 'outsource') ? 'block' : 'none';
    }

    // ========== MULTI-PIECE FUNCTIONALITY ==========
    function togglePieceMode() {
        const count = parseInt(document.getElementById('pieces_count').value);
        const single = document.getElementById('single_piece_fields');
        const multi = document.getElementById('multi_piece_container');
        const printCard = document.getElementById('printing-config-card');
        
        if (count > 1) {
            single.style.display = 'none';
            multi.style.display = 'block';
            if (printCard) printCard.style.display = 'none';
            document.getElementById('main_packing_div').style.display = 'none';
            document.getElementById('global_finishing_instruction_row').style.display = 'none';
            generatePieceTabs(count);
        } else {
            single.style.display = 'block';
            multi.style.display = 'none';
            if (printCard) printCard.style.display = 'block';
            document.getElementById('main_packing_div').style.display = 'block';
            document.getElementById('global_finishing_instruction_row').style.display = 'block';
        }
    }

    function generatePieceTabs(count) {
        const tabsContainer = document.getElementById('pieceTabs');
        const contentContainer = document.getElementById('pieceTabContent');
        tabsContainer.innerHTML = '';
        contentContainer.innerHTML = '';
        
        for (let i = 0; i < count; i++) {
            const piece = existingPieces[i] || null;
            const tabId = `piece-tab-${i}`;
            const paneId = `piece-pane-${i}`;
            
            const tab = document.createElement('li');
            tab.className = 'nav-item';
            tab.innerHTML = `<button class="nav-link ${i === 0 ? 'active' : ''}" id="${tabId}" data-bs-toggle="tab" data-bs-target="#${paneId}" type="button">Piece ${i + 1} ${piece ? `(${piece.piece_name})` : ''}</button>`;
            tabsContainer.appendChild(tab);

            const pane = document.createElement('div');
            pane.className = `tab-pane fade ${i === 0 ? 'show active' : ''}`;
            pane.id = paneId;
            pane.innerHTML = generatePieceForm(i, piece);
            contentContainer.appendChild(pane);
            
            if (piece) {
                renderPieceLayers(i, piece.ply_type, piece.layers);
                renderPieceInkFields(i, piece.print_colors, piece.printing_data ? piece.printing_data.inks : []);
            }
        }
    }

    function generatePieceForm(index, piece = null) {
        return `
            <div class="piece-form p-3">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label>Piece Name</label>
                        <input type="text" name="pieces[${index}][piece_name]" class="form-control" value="${piece ? (piece.piece_name || '') : ''}" placeholder="Lid, Base, etc.">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3"><label>Length (mm)</label><input type="number" step="0.01" name="pieces[${index}][length]" class="form-control" value="${piece ? (piece.length || '') : ''}"></div>
                    <div class="col-md-4 mb-3"><label>Width (mm)</label><input type="number" step="0.01" name="pieces[${index}][width]" class="form-control" value="${piece ? (piece.width || '') : ''}"></div>
                    <div class="col-md-4 mb-3"><label>Height (mm)</label><input type="number" step="0.01" name="pieces[${index}][height]" class="form-control" value="${piece ? (piece.height || '') : ''}"></div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3"><label>Deckle Size (Inch)</label><input type="number" step="0.01" name="pieces[${index}][deckle_size]" class="form-control" value="${piece ? (piece.deckle_size || '') : ''}"></div>
                    <div class="col-md-3 mb-3"><label>Sheet Length (Inch)</label><input type="number" step="0.01" name="pieces[${index}][sheet_length]" class="form-control" value="${piece ? (piece.sheet_length || '') : ''}"></div>
                    <div class="col-md-3 mb-3">
                        <label>Ply Type</label>
                        <select name="pieces[${index}][ply_type]" id="piece${index}_ply" class="form-control" onchange="renderPieceLayers(${index}, this.value)">
                            <option value="">Select Ply</option>
                            <option value="3" ${piece && piece.ply_type == 3 ? 'selected' : ''}>3-Ply</option>
                            <option value="5" ${piece && piece.ply_type == 5 ? 'selected' : ''}>5-Ply</option>
                            <option value="7" ${piece && piece.ply_type == 7 ? 'selected' : ''}>7-Ply</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3"><label>UPS</label><input type="number" name="pieces[${index}][ups]" class="form-control" value="${piece ? (piece.ups || 1) : 1}"></div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Print Colors</label>
                        <select name="pieces[${index}][print_colors]" id="piece${index}_print_colors" class="form-control" onchange="renderPieceInkFields(${index}, this.value)">
                            <option value="0" ${piece && piece.print_colors == 0 ? 'selected' : ''}>Un-Printed</option>
                            <option value="1" ${piece && piece.print_colors == 1 ? 'selected' : ''}>1-Color</option>
                            <option value="2" ${piece && piece.print_colors == 2 ? 'selected' : ''}>2-Color</option>
                            <option value="3" ${piece && piece.print_colors == 3 ? 'selected' : ''}>3-Color</option>
                            <option value="4" ${piece && piece.print_colors == 4 ? 'selected' : ''}>4-Color</option>
                            <option value="5" ${piece && piece.print_colors == 5 ? 'selected' : ''}>5-Color</option>
                        </select>
                    </div>
                </div>

                <div class="row" id="piece${index}_ink_fields"></div>

                <div class="row">
                    <div class="col-md-6 mb-3"><label>Packing Qty (per Bundle)</label><input type="number" name="pieces[${index}][packing_bundle_qty]" class="form-control" value="${piece ? (piece.packing_bundle_qty || '') : ''}"></div>
                    <div class="col-md-6 mb-3">
                        <label>Packing Type</label>
                        <select name="pieces[${index}][packing_type]" class="form-control">
                            <option value="">Select Type</option>
                            <option value="Strapping" ${piece && piece.packing_type === 'Strapping' ? 'selected' : ''}>Strapping</option>
                            <option value="Packed in Box" ${piece && piece.packing_type === 'Packed in Box' ? 'selected' : ''}>Packed in Box</option>
                        </select>
                    </div>
                </div>
                
                <!-- Slitting Creasing -->
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label>Slitting Creasing</label>
                        <select name="pieces[${index}][slitting_creasing]" class="form-control">
                            <option value="">Select Option</option>
                            <option value="Slitting" ${piece && piece.slitting_creasing === 'Slitting' ? 'selected' : ''}>Slitting</option>
                            <option value="Creasing" ${piece && piece.slitting_creasing === 'Creasing' ? 'selected' : ''}>Creasing</option>
                            <option value="Both" ${piece && piece.slitting_creasing === 'Both' ? 'selected' : ''}>Both</option>
                            <option value="None" ${piece && piece.slitting_creasing === 'None' ? 'selected' : ''}>None</option>
                        </select>
                    </div>
                </div>

                <!-- Per Piece Special Instructions -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Piece Corrugation Instruction</label>
                        <textarea name="pieces[${index}][corrugation_instruction]" class="form-control" rows="2">${piece ? (piece.corrugation_instruction || '') : ''}</textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Piece Printing Instruction</label>
                        <textarea name="pieces[${index}][printing_instruction]" class="form-control" rows="2">${piece ? (piece.printing_instruction || '') : ''}</textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Piece Finishing Instruction</label>
                        <textarea name="pieces[${index}][finishing_instruction]" class="form-control" rows="2">${piece ? (piece.finishing_instruction || '') : ''}</textarea>
                    </div>
                </div>
                
                <div id="piece${index}_layers_container" class="mt-3 p-3 border rounded shadow-sm bg-white" style="display:none;">
                    <h6 class="mb-3">Piece ${index + 1} Paper Structure</h6>
                    <div id="piece${index}_layers_list"></div>
                </div>
            </div>
        `;
    }

    function renderPieceLayers(index, ply, existing = []) {
        const container = document.getElementById(`piece${index}_layers_container`);
        const list = document.getElementById(`piece${index}_layers_list`);
        if (!list) return;
        list.innerHTML = '';
        if (!ply) { container.style.display = 'none'; return; }
        container.style.display = 'block';

        let structure = [];
        if(ply == 3) structure = [{ label: 'Top', type: 'Top Layer' }, { label: 'Flute', type: 'Flute Layer', hasFlute: true }, { label: 'Inner', type: 'Inner Layer' }];
        else if(ply == 5) structure = [{ label: 'Top', type: 'Top Layer' }, { label: 'Flute 1', type: 'Flute Layer', hasFlute: true }, { label: 'Center', type: 'Center Layer' }, { label: 'Flute 2', type: 'Flute Layer', hasFlute: true }, { label: 'Inner', type: 'Inner Layer' }];
        else if(ply == 7) structure = [{ label: 'Top', type: 'Top Layer' }, { label: 'Flute 1', type: 'Flute Layer', hasFlute: true }, { label: 'C1', type: 'Center Layer' }, { label: 'Flute 2', type: 'Flute Layer', hasFlute: true }, { label: 'C2', type: 'Center Layer' }, { label: 'Flute 3', type: 'Flute Layer', hasFlute: true }, { label: 'Inner', type: 'Inner Layer' }];

        structure.forEach((layer, lIdx) => {
            const current = existing[lIdx] || {};
            let paperOptions = '<option value="">Select Paper</option>';
            papers.forEach(p => paperOptions += `<option value="${p.name}" ${current.paper_name === p.name ? 'selected' : ''}>${p.name}</option>`);
            
            let fluteHtml = layer.hasFlute ? `
                <select name="pieces[${index}][layers][${lIdx}][flute_type]" class="form-control form-control-sm">
                    <option value="">Flute</option>
                    <option value="A" ${current.flute_type === 'A' ? 'selected' : ''}>A</option>
                    <option value="B" ${current.flute_type === 'B' ? 'selected' : ''}>B</option>
                    <option value="C" ${current.flute_type === 'C' ? 'selected' : ''}>C</option>
                    <option value="E" ${current.flute_type === 'E' ? 'selected' : ''}>E</option>
                </select>
            ` : '<span class="form-control form-control-sm bg-light">-</span>';

            list.insertAdjacentHTML('beforeend', `
                <div class="row g-2 mb-2 align-items-center">
                    <div class="col-md-2"><strong>${layer.label}</strong><input type="hidden" name="pieces[${index}][layers][${lIdx}][type]" value="${layer.type}"></div>
                    <div class="col-md-5"><select name="pieces[${index}][layers][${lIdx}][paper_name]" class="form-control form-control-sm" required>${paperOptions}</select></div>
                    <div class="col-md-2"><input type="number" name="pieces[${index}][layers][${lIdx}][gsm]" class="form-control form-control-sm" value="${current.gsm || ''}" placeholder="GSM" required></div>
                    <div class="col-md-3">${fluteHtml}</div>
                </div>
            `);
        });
    }

    function renderPieceInkFields(index, count, currentInks = []) {
        const container = document.getElementById(`piece${index}_ink_fields`);
        if (!container) return;
        container.innerHTML = '';
        if (count == 0) return;
        
        for (let i = 0; i < count; i++) {
            let options = '<option value="">Select Ink</option>';
            inks.forEach(ink => options += `<option value="${ink.id}" ${currentInks[i] == ink.id ? 'selected' : ''}>${ink.color_name}</option>`);
            container.insertAdjacentHTML('beforeend', `<div class="col-md-4 mb-2"><label>Ink ${i+1}</label><select name="pieces[${index}][printing_data][inks][]" class="form-control form-control-sm">${options}</select></div>`);
        }
    }

    // Initial Trigger
    if (existingPieces && existingPieces.length > 0) {
        togglePieceMode();
    } else {
        renderLayers();
        renderInkFields();
    }
    toggleStaple(document.querySelector('select[name="pasting_type"]').value);
    toggleSpecial('honeycomb');
    toggleSpecial('separator');
    updateCartonPreview(); // Show preview on page load

    // Die-Line Preview Logic
    let currentSVG = null;
    function generateDieLine() {
        const length = document.getElementById('dimension_length').value;
        const width = document.getElementById('dimension_width').value;
        const height = document.getElementById('dimension_height').value;
        const cartonTypeSelect = document.querySelector('select[name="carton_type_id"]');
        const selectedOption = cartonTypeSelect.options[cartonTypeSelect.selectedIndex];
        const fefcoCode = selectedOption ? (selectedOption.dataset.code || selectedOption.text.match(/\(([^)]+)\)/)?.[1] || '0201') : '0201';

        if (!length || !width || !height) { alert('Please enter Length, Width, and Height.'); return; }

        const modal = new bootstrap.Modal(document.getElementById('dieLineModal'));
        modal.show();
        
        document.getElementById('dieline-loading').style.display = 'block';
        document.getElementById('dieline-container').innerHTML = '';

        fetch('{{ route("job-cards.generate-dieline") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ length: parseFloat(length), width: parseFloat(width), height: parseFloat(height), fefco_code: fefcoCode })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('dieline-loading').style.display = 'none';
            if (data.success) { currentSVG = data.svg; document.getElementById('dieline-container').innerHTML = data.svg; }
            else alert('Error');
        }).catch(() => document.getElementById('dieline-loading').style.display = 'none');
    }

    function downloadJPEG() {
        if (!currentSVG) return;
        const svg = document.querySelector('#dieline-container svg');
        const serializer = new XMLSerializer();
        const source = serializer.serializeToString(svg);
        const img = new Image();
        img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(source)));
        img.onload = function() {
            const canvas = document.createElement('canvas');
            canvas.width = img.width; canvas.height = img.height;
            const ctx = canvas.getContext('2d');
            ctx.fillStyle = 'white'; ctx.fillRect(0,0,canvas.width,canvas.height);
            ctx.drawImage(img, 0, 0);
            const a = document.createElement('a');
            a.download = 'dieline.jpg'; a.href = canvas.toDataURL('image/jpeg'); a.click();
        };
    }
</script>
@endsection
