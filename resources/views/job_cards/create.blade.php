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
    <h2>Create New Job Card</h2>
    
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

    <form action="{{ route('job-cards.store') }}" method="POST" id="jobCardForm">
        @csrf
        
        <!-- BASIC INFO -->
        <div class="card mb-3">
            <div class="card-header">Basic Information</div>
            <div class="card-body">
                <div class="row">
                    <!-- Left Column (Inputs) -->
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Customer</label>
                                <select name="customer_id" class="form-control" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Item Code</label>
                                <input type="text" name="item_code" class="form-control" value="{{ old('item_code') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label>Carton Type</label>
                                <select name="carton_type_id" id="carton_type_id" class="form-control" required onchange="updateCartonPreview()">
                                    <option value="">Select Type</option>
                                    @foreach($cartonTypes as $t)
                                        <option value="{{ $t->id }}" data-code="{{ $t->standard_code }}" {{ old('carton_type_id') == $t->id ? 'selected' : '' }}>{{ $t->name }} ({{ $t->standard_code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Item Name</label>
                                <input type="text" name="item_name" class="form-control" value="{{ old('item_name') }}" required>
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
                            <option value="mm" {{ old('uom') == 'mm' ? 'selected' : '' }}>MM</option>
                            <option value="inch" {{ old('uom') == 'inch' ? 'selected' : '' }}>Inch</option>
                            <option value="cm" {{ old('uom') == 'cm' ? 'selected' : '' }}>CM</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Inner Length</label>
                        <input type="number" step="0.01" name="length" id="dimension_length" class="form-control" value="{{ old('length') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Inner Width</label>
                        <input type="number" step="0.01" name="width" id="dimension_width" class="form-control" value="{{ old('width') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Inner Height</label>
                        <input type="number" step="0.01" name="height" id="dimension_height" class="form-control" value="{{ old('height') }}">
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
                            <option value="1" {{ old('pieces_count', 1) == 1 ? 'selected' : '' }}>1 Piece (Standard)</option>
                            <option value="2" {{ old('pieces_count') == 2 ? 'selected' : '' }}>2 Pieces (e.g., Lid & Base)</option>
                            <option value="3" {{ old('pieces_count') == 3 ? 'selected' : '' }}>3 Pieces</option>
                            <option value="4" {{ old('pieces_count') == 4 ? 'selected' : '' }}>4 Pieces</option>
                            <option value="5" {{ old('pieces_count') == 5 ? 'selected' : '' }}>5 Pieces</option>
                        </select>
                        <small class="text-muted">Select number of separate pieces for this carton</small>
                    </div>
                </div>

                <!-- SINGLE PIECE FIELDS (Hidden when multi-piece) -->
                <div id="single_piece_fields">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Deckle Size Inch</label>
                            <input type="number" step="0.01" id="deckle_size_calc" class="form-control" readonly style="background-color: #e9ecef;">
                            <small class="text-muted">(W+H)+25</small>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Sheet Length Inch</label>
                            <input type="number" step="0.01" id="sheet_length_calc" class="form-control" readonly style="background-color: #e9ecef;">
                            <small class="text-muted">(L+L+W+W+75)</small>
                        </div>
                    </div>

                    <div class="row">
                       <div class="col-md-4 mb-3">
                            <label>Deckle Size (Inch)</label>
                            <input type="number" step="0.01" name="deckle_size" class="form-control" value="{{ old('deckle_size') }}">
                       </div>
                       <div class="col-md-4 mb-3">
                            <label>Sheet Length (Inch)</label>
                            <input type="number" step="0.01" name="sheet_length" class="form-control" value="{{ old('sheet_length') }}">
                       </div>
                       <div class="col-md-4 mb-3">
                            <label>UPS (Cartons/Sheet)</label>
                            <input type="number" name="ups" class="form-control" value="{{ old('ups', 1) }}">
                       </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Ply Type</label>
                            <select name="ply_type" id="ply_type" class="form-control" onchange="renderLayers()">
                                <option value="">Select Ply</option>
                                <option value="3" {{ old('ply_type') == '3' ? 'selected' : '' }}>3-Ply</option>
                                <option value="5" {{ old('ply_type') == '5' ? 'selected' : '' }}>5-Ply</option>
                                <option value="7" {{ old('ply_type') == '7' ? 'selected' : '' }}>7-Ply</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Slitting Creasing</label>
                            <select name="slitting_creasing" class="form-control">
                                <option value="">Select Option</option>
                                <option value="Plant Online" {{ old('slitting_creasing') == 'Plant Online' ? 'selected' : '' }}>Plant Online</option>
                                <option value="Manual" {{ old('slitting_creasing') == 'Manual' ? 'selected' : '' }}>Manual</option>
                                <option value="Die Cutting" {{ old('slitting_creasing') == 'Die Cutting' ? 'selected' : '' }}>Die Cutting</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- MULTI-PIECE CONFIGURATION (Hidden by default) -->
        <div id="multi_piece_container" style="display:none;">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    Multi-Piece Configuration
                    <small class="float-end">Configure each piece separately</small>
                </div>
                <div class="card-body">
                    <!-- Nav tabs for pieces -->
                    <ul class="nav nav-tabs" id="pieceTabs" role="tablist">
                        <!-- Tabs will be generated by JavaScript -->
                    </ul>
                    
                    <!-- Tab content -->
                    <div class="tab-content mt-3" id="pieceTabContent">
                        <!-- Piece forms will be generated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        <!-- PAPER STRUCTURE LOGIC (For single piece) -->
        <div class="card mb-3" id="paper-structure-card" style="display:none;">
            <div class="card-header">Paper Structure Configuration</div>
            <div class="card-body" id="layers-container">
                <!-- Javascript will render rows here -->
            </div>
        </div>

        <!-- PRINTING CONFIG (Hidden for multi-piece) -->
        <div class="card mb-3" id="printing-config-card">
            <div class="card-header">Printing Configuration</div>
            <div class="card-body">
                <div class="mb-3">
                    <label>No. of Printing Colors</label>
                    <select name="print_colors" id="print_colors" class="form-control" onchange="renderInkFields()">
                        <option value="0">Un-Printed</option>
                        <option value="1">1-Color</option>
                        <option value="2">2-Color</option>
                        <option value="3">3-Color</option>
                        <option value="4">4-Color</option>
                        <option value="5">5-Color</option>
                    </select>
                </div>
                <div id="ink-fields-container" class="row"></div>

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
                            <option value="None">No Pasting</option>
                            <option value="Glue">Glue</option>
                            <option value="Staple">Stapling</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3" id="staple_div" style="display:none;">
                        <label>Staple Details</label>
                        <input type="text" name="staple_details" class="form-control" placeholder="No. of Pins">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Process Type</label>
                        <select name="process_type" class="form-control">
                            <option value="Rotary Slotter">Rotary Slotter</option>
                            <option value="Die Cutting">Die Cutting</option>
                        </select>
                    </div>
                </div>
                <!-- Special Cases -->
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_honeycomb" onchange="toggleSpecial('honeycomb')">
                    <label class="form-check-label">Honeycomb Add-on</label>
                </div>
                <div id="honeycomb_fields" class="mb-3 p-3 border bg-light" style="display:none;">
                    <h5 class="mb-3">Honeycomb Specifications</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Honeycomb Holes</label>
                            <input type="number" name="special_details[honeycomb][holes]" class="form-control" placeholder="e.g., 100">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Plies</label>
                            <select name="special_details[honeycomb][plies]" class="form-control">
                                <option value="">Select Plies</option>
                                <option value="3">3-Ply</option>
                                <option value="5">5-Ply</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>UOM</label>
                            <select name="special_details[honeycomb][uom]" class="form-control">
                                <option value="mm">MM</option>
                                <option value="inch">Inch</option>
                                <option value="cm">CM</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Length</label>
                            <input type="number" step="0.01" name="special_details[honeycomb][length]" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Width</label>
                            <input type="number" step="0.01" name="special_details[honeycomb][width]" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Height</label>
                            <input type="number" step="0.01" name="special_details[honeycomb][height]" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Hole Size (L x W)</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" step="0.01" name="special_details[honeycomb][hole_length]" class="form-control" placeholder="Length">
                                </div>
                                <div class="col-6">
                                    <input type="number" step="0.01" name="special_details[honeycomb][hole_width]" class="form-control" placeholder="Width">
                                </div>
                            </div>
                            <small class="text-muted">Enter the dimensions of each honeycomb hole</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Material (Paper Construction)</label>
                            <input type="text" name="special_details[honeycomb][material]" class="form-control" placeholder="e.g., Kraft Paper">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Source</label>
                            <select name="special_details[honeycomb][source]" class="form-control" onchange="toggleHoneycombSupplier(this.value)">
                                <option value="">Select Source</option>
                                <option value="in_house">Made In-House</option>
                                <option value="outsource">Outsource</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" id="honeycomb_supplier_row" style="display:none;">
                        <div class="col-md-12 mb-3">
                            <label>Supplier Name</label>
                            <input type="text" name="special_details[honeycomb][supplier_name]" class="form-control" placeholder="Enter supplier name">
                        </div>
                    </div>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_separator" onchange="toggleSpecial('separator')">
                    <label class="form-check-label">Separator Add-on</label>
                </div>
                <div id="separator_fields" class="mb-3 p-3 border bg-light" style="display:none;">
                     <h5 class="mb-3">Separator Specifications</h5>
                     <div class="row">
                         <div class="col-md-4 mb-3">
                             <label>Plies</label>
                             <select name="special_details[separator][plies]" class="form-control">
                                 <option value="">Select Plies</option>
                                 <option value="2">2-Ply</option>
                                 <option value="3">3-Ply</option>
                                 <option value="4">4-Ply</option>
                                 <option value="5">5-Ply</option>
                             </select>
                         </div>
                         <div class="col-md-4 mb-3">
                             <label>UOM</label>
                             <select name="special_details[separator][uom]" class="form-control">
                                 <option value="mm">MM</option>
                                 <option value="inch">Inch</option>
                                 <option value="cm">CM</option>
                             </select>
                         </div>
                     </div>
                     <div class="row">
                         <div class="col-md-6 mb-3">
                             <label>Length</label>
                             <input type="number" step="0.01" name="special_details[separator][length]" class="form-control">
                         </div>
                         <div class="col-md-6 mb-3">
                             <label>Width</label>
                             <input type="number" step="0.01" name="special_details[separator][width]" class="form-control">
                         </div>
                     </div>
                     <div class="row">
                         <div class="col-md-6 mb-3">
                             <label>Material (Paper Construction)</label>
                             <input type="text" name="special_details[separator][material]" class="form-control" placeholder="e.g., Kraft Paper">
                         </div>
                         <div class="col-md-6 mb-3">
                             <label>Source</label>
                             <select name="special_details[separator][source]" class="form-control" onchange="toggleSeparatorSupplier(this.value)">
                                 <option value="">Select Source</option>
                                 <option value="in_house">Made In-House</option>
                                 <option value="outsource">Outsource</option>
                             </select>
                         </div>
                     </div>
                     <div class="row" id="separator_supplier_row" style="display:none;">
                         <div class="col-md-12 mb-3">
                             <label>Supplier Name</label>
                             <input type="text" name="special_details[separator][supplier_name]" class="form-control" placeholder="Enter supplier name">
                         </div>
                     </div>
                </div>



                <div id="main_packing_div">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Packing Qty (Cartons per Bundle)</label>
                            <input type="number" name="packing_bundle_qty" class="form-control" placeholder="Enter quantity">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Packing Type</label>
                            <select name="packing_type" class="form-control">
                                <option value="">Select Packing Type</option>
                                <option value="Strapping">Strapping</option>
                                <option value="Packed in Box">Packed in Box</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DEPARTMENT INSTRUCTIONS (Always Visible) -->
        <div class="card mb-3" id="global_instructions_card">
            <div class="card-header">Department Special Instructions</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label>Corrugation Special Instruction</label>
                        <textarea name="corrugation_instruction" class="form-control" rows="2" placeholder="Instructions for Corrugation Plant...">{{ old('corrugation_instruction') }}</textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Printing Special Instruction</label>
                        <textarea name="printing_instruction" class="form-control" rows="2" placeholder="Instructions for Printing Department...">{{ old('printing_instruction') }}</textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Finishing Special Instruction</label>
                        <textarea name="finishing_instruction" class="form-control" rows="2" placeholder="Instructions for Finishing/Pasting...">{{ old('finishing_instruction') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">Additional Remarks</div>
            <div class="card-body">
                <div class="mb-3">
                    <label>Remarks</label>
                    <textarea name="remarks" class="form-control"></textarea>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg">Create Job Card</button>
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
    // Pass PHP data to JS
    const inks = @json($inks);
    const papers = @json($papers);

// Calculate Deckle Size and Sheet Length
function updateCalculations() {
    let length = parseFloat(document.getElementById('dimension_length').value) || 0;
    let width = parseFloat(document.getElementById('dimension_width').value) || 0;
    let height = parseFloat(document.getElementById('dimension_height').value) || 0;
    const uom = document.getElementById('uom').value;

    // Convert to inches for calculation logic if needed, but the formula given was (W+H)+25. 
    // Assuming the "25" and "75" constants were originally for MM.
    // If the user wants the Output in INCH, and the constants are for MM, we need to be careful.
    // However, usually in MM based plants, Deckle is MM. But user specifically asked "Deckle Size (mm/inch) should be Deckle Size (inch)".
    // And "Deckle Size Inch and Sheet Length Inch dynamically calculated as per scale uom".
    
    // Let's standardise everything to MM first, then apply formula, then convert to Inch.
    // OR if the formula (W+H)+25 is meant for MM inputs resulting in MM outputs.

    // Let's assume the previous formula was for MM inputs:
    // Deckle (mm) = (W + H) + 25 (allowance)
    // Sheet Length (mm) = (L*2) + (W*2) + 75 (allowance)
    
    // Convert inputs to MM first
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

    // Calculate dimensions in MM (assuming standard allowances are in MM: 25mm and 75mm)
    // Deckle = Width + Height + 25mm (trim/allowance)
    const deckle_mm = (w_mm + h_mm) + 25;
    
    // Sheet Length = (L x 2) + (W x 2) + 75mm (joint/allowance)
    const sheet_length_mm = (l_mm * 2) + (w_mm * 2) + 75;

    // Convert Final Results to INCH
    const deckle_inch = deckle_mm / 25.4;
    const sheet_length_inch = sheet_length_mm / 25.4;

    document.getElementById('deckle_size_calc').value = deckle_inch.toFixed(2);
    document.getElementById('sheet_length_calc').value = sheet_length_inch.toFixed(2);

    // Also update the hidden/actual fields if they are empty or user hasn't manually edited them (optional enhancement)
    // For now, let's just update the visible calculated fields as requested.
}

document.getElementById('uom').addEventListener('change', updateCalculations);
document.getElementById('dimension_length').addEventListener('input', updateCalculations);
document.getElementById('dimension_width').addEventListener('input', updateCalculations);
document.getElementById('dimension_height').addEventListener('input', updateCalculations);

// Initial calculation on page load
// updateCalculations(); // moved to end of script


// Initial calculation on page load
updateCalculations();
updateCartonPreview();

function updateCartonPreview() {
    const select = document.getElementById('carton_type_id');
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

// Existing renderLayers function
function renderLayers() {
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
            { label: 'Outer Paper', type: 'Outer' },
            { label: 'Flute', type: 'Flute', hasFluteType: true },
            { label: 'Inner Paper', type: 'Inner' }
        ];
    } else if(ply === '5') {
        structure = [
            { label: 'Outer Paper', type: 'Outer' },
            { label: 'Flute 1', type: 'Flute', hasFluteType: true },
            { label: 'Middle Paper', type: 'Middle' },
            { label: 'Flute 2', type: 'Flute', hasFluteType: true },
            { label: 'Inner Paper', type: 'Inner' }
        ];
    } else if(ply === '7') {
        structure = [
             { label: 'Outer Paper', type: 'Outer' },
             { label: 'Flute 1', type: 'Flute', hasFluteType: true },
             { label: 'Middle Paper 1', type: 'Middle' },
             { label: 'Flute 2', type: 'Flute', hasFluteType: true },
             { label: 'Middle Paper 2', type: 'Middle' },
             { label: 'Flute 3', type: 'Flute', hasFluteType: true },
             { label: 'Inner Paper', type: 'Inner' }
        ];
    }

    structure.forEach((layer, index) => {
        let fluteOptions = '';
        if(layer.hasFluteType) {
            fluteOptions = `
                <div class="col-md-2">
                    <label>Flute Type</label>
                    <select name="layers[${index}][flute_type]" class="form-control">
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="E">E</option>
                    </select>
                </div>
            `;
        }

        let paperOptions = '<option value="">Select Paper</option>';
        papers.forEach(p => {
             paperOptions += `<option value="${p.name}" data-gsm="${p.gsm}">${p.name} (${p.gsm} GSM)</option>`;
        });

        let html = `
            <div class="row mb-2 border-bottom pb-2">
                <div class="col-md-3">
                    <strong>${layer.label}</strong>
                    <input type="hidden" name="layers[${index}][type]" value="${layer.type}">
                </div>
                <div class="col-md-4">
                    <label>Paper Name</label>
                    <select name="layers[${index}][paper_name]" class="form-control paper-select" required onchange="updateGSM(this, ${index})">
                        ${paperOptions}
                    </select>
                </div>
                <div class="col-md-2">
                    <label>GSM</label>
                    <input type="number" name="layers[${index}][gsm]" id="gsm_${index}" class="form-control" required readonly>
                </div>
                ${fluteOptions}
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    });
}

function updateGSM(select, index) {
    const option = select.options[select.selectedIndex];
    const gsm = option.getAttribute('data-gsm');
    document.getElementById(`gsm_${index}`).value = gsm || '';
}

function renderInkFields() {
    const count = document.getElementById('print_colors').value;
    const container = document.getElementById('ink-fields-container');
    container.innerHTML = '';

    for(let i=0; i<count; i++) {
        let options = '<option value="">Select Ink</option>';
        inks.forEach(ink => {
            options += `<option value="${ink.id}">${ink.color_name} (${ink.color_code})</option>`;
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
    document.getElementById('honeycomb_supplier_row').style.display = (source === 'outsource') ? 'block' : 'none';
}

function toggleSeparatorSupplier(source) {
    document.getElementById('separator_supplier_row').style.display = (source === 'outsource') ? 'block' : 'none';
}

// Die-Line Generation
let currentSVG = null;

function generateDieLine() {
    const length = document.getElementById('dimension_length').value;
    const width = document.getElementById('dimension_width').value;
    const height = document.getElementById('dimension_height').value;
    const itemName = document.querySelector('input[name="item_name"]').value;
    const cartonTypeSelect = document.querySelector('select[name="carton_type_id"]');
    const selectedOption = cartonTypeSelect.options[cartonTypeSelect.selectedIndex];
    
    // Get FEFCO code from data-code attribute
    let fefcoCode = '0201'; // default
    if (selectedOption && selectedOption.dataset.code) {
        fefcoCode = selectedOption.dataset.code;
    }
    
    console.log('Selected Carton Type:', selectedOption ? selectedOption.text : 'None');
    console.log('Extracted FEFCO Code:', fefcoCode);

    if (!length || !width || !height) {
        alert('Please enter Length, Width, and Height dimensions first.');
        return;
    }

    // Show modal and loading using Bootstrap 5 API
    const modalElement = document.getElementById('dieLineModal');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
    
    document.getElementById('dieline-loading').style.display = 'block';
    document.getElementById('dieline-container').innerHTML = '';

    // AJAX request to generate die-line
    fetch('{{ route("job-cards.generate-dieline") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            length: parseFloat(length),
            width: parseFloat(width),
            height: parseFloat(height),
            item_name: itemName,
            fefco_code: fefcoCode || '0201'
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('dieline-loading').style.display = 'none';
        
        if (data.success) {
            currentSVG = data.svg;
            document.getElementById('dieline-container').innerHTML = data.svg;
        } else {
            alert('Error generating die-line');
        }
    })
    .catch(error => {
        document.getElementById('dieline-loading').style.display = 'none';
        console.error('Error:', error);
        alert('Error generating die-line');
    });
}

function downloadJPEG() {
    if (!currentSVG) {
        alert('No die-line generated yet');
        return;
    }

    const svgElement = document.querySelector('#dieline-container svg');
    if (!svgElement) {
        alert('SVG not found');
        return;
    }

    // Get SVG dimensions
    const svgData = new XMLSerializer().serializeToString(svgElement);
    const canvas = document.getElementById('dieline-canvas');
    const ctx = canvas.getContext('2d');
    const img = new Image();

    // Create blob from SVG
    const blob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });
    const url = URL.createObjectURL(blob);

    img.onload = function() {
        canvas.width = img.width;
        canvas.height = img.height;
        
        // Fill white background
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        // Draw SVG
        ctx.drawImage(img, 0, 0);
        
        // Convert to JPEG and download
        canvas.toBlob(function(blob) {
            const link = document.createElement('a');
            link.download = `dieline_${Date.now()}.jpg`;
            link.href = URL.createObjectURL(blob);
            link.click();
            URL.revokeObjectURL(url);
        }, 'image/jpeg', 0.95);
    };

    img.src = url;
}

// ========== MULTI-PIECE FUNCTIONALITY ==========

function togglePieceMode() {
    const piecesCount = parseInt(document.getElementById('pieces_count').value);
    const singleFields = document.getElementById('single_piece_fields');
    const multiContainer = document.getElementById('multi_piece_container');
    const paperStructureCard = document.getElementById('paper-structure-card');
    const printingConfigCard = document.getElementById('printing-config-card');
    
    if (piecesCount > 1) {
        // Multi-piece mode
        singleFields.style.display = 'none';
        multiContainer.style.display = 'block';
        paperStructureCard.style.display = 'none';
        printingConfigCard.style.display = 'none'; // Hide main printing config
        document.getElementById('main_packing_div').style.display = 'none';
        document.getElementById('global_instructions_card').style.display = 'none';
        generatePieceTabs(piecesCount);
    } else {
        // Single piece mode
        singleFields.style.display = 'block';
        multiContainer.style.display = 'none';
        printingConfigCard.style.display = 'block'; // Show main printing config
        document.getElementById('main_packing_div').style.display = 'block';
        document.getElementById('global_instructions_card').style.display = 'block';
        // Paper structure card will be shown by renderLayers() when ply is selected
    }
}

function generatePieceTabs(count) {
    const tabsContainer = document.getElementById('pieceTabs');
    const contentContainer = document.getElementById('pieceTabContent');
    
    tabsContainer.innerHTML = '';
    contentContainer.innerHTML = '';
    
    for (let i = 0; i < count; i++) {
        // Create tab
        const tab = document.createElement('li');
        tab.className = 'nav-item';
        tab.innerHTML = `
            <button class="nav-link ${i === 0 ? 'active' : ''}" 
                    id="piece${i}-tab" 
                    data-bs-toggle="tab" 
                    data-bs-target="#piece${i}" 
                    type="button">
                Piece ${i + 1}
            </button>
        `;
        tabsContainer.appendChild(tab);
        
        // Create tab content
        const content = document.createElement('div');
        content.className = `tab-pane fade ${i === 0 ? 'show active' : ''}`;
        content.id = `piece${i}`;
        content.innerHTML = generatePieceForm(i);
        contentContainer.appendChild(content);
    }
}

function generatePieceForm(pieceIndex) {
    return `
        <div class="piece-form">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label>Piece Name</label>
                    <input type="text" name="pieces[${pieceIndex}][piece_name]" 
                           class="form-control" 
                           placeholder="e.g., Lid, Base, Piece ${pieceIndex + 1}">
                    <small class="text-muted">Optional: Give this piece a descriptive name</small>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Length (mm)</label>
                    <input type="number" step="0.01" 
                           name="pieces[${pieceIndex}][length]" 
                           class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Width (mm)</label>
                    <input type="number" step="0.01" 
                           name="pieces[${pieceIndex}][width]" 
                           class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Height (mm)</label>
                    <input type="number" step="0.01" 
                           name="pieces[${pieceIndex}][height]" 
                           class="form-control">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label>Deckle Size (Inch)</label>
                    <input type="number" step="0.01" 
                           name="pieces[${pieceIndex}][deckle_size]" 
                           class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label>Sheet Length (Inch)</label>
                    <input type="number" step="0.01" 
                           name="pieces[${pieceIndex}][sheet_length]" 
                           class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label>Ply Type</label>
                    <select name="pieces[${pieceIndex}][ply_type]" 
                            id="piece${pieceIndex}_ply" 
                            class="form-control" 
                            onchange="renderPieceLayers(${pieceIndex})">
                        <option value="">Select Ply</option>
                        <option value="3">3-Ply</option>
                        <option value="5">5-Ply</option>
                        <option value="7">7-Ply</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label>UPS (Cartons/Sheet)</label>
                    <input type="number" 
                           name="pieces[${pieceIndex}][ups]" 
                           class="form-control"
                       min="1">
                </div>
            </div>
            
            <!-- Printing Configuration -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label><strong>Printing Configuration</strong></label>
                    <select name="pieces[${pieceIndex}][print_colors]" 
                            id="piece${pieceIndex}_print_colors"
                            class="form-control" 
                            onchange="renderPieceInkFields(${pieceIndex})">
                        <option value="0">Un-Printed</option>
                        <option value="1">1-Color</option>
                        <option value="2">2-Color</option>
                        <option value="3">3-Color</option>
                        <option value="4">4-Color</option>
                        <option value="5">5-Color</option>
                    </select>
                </div>
            </div>
            <div class="row" id="piece${pieceIndex}_ink_fields">
                <!-- Ink selection fields will be rendered here -->
            </div>
            
            <!-- Packing -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Packing Qty (per Bundle)</label>
                    <input type="number" 
                           name="pieces[${pieceIndex}][packing_bundle_qty]" 
                           class="form-control"
                           placeholder="e.g., 50">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Packing Type</label>
                    <select name="pieces[${pieceIndex}][packing_type]" class="form-control">
                        <option value="">Select Type</option>
                        <option value="Strapping">Strapping</option>
                        <option value="Packed in Box">Packed in Box</option>
                    </select>
                </div>
            </div>
            
            <!-- Slitting Creasing -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label>Slitting Creasing</label>
                    <select name="pieces[${pieceIndex}][slitting_creasing]" class="form-control">
                        <option value="">Select Option</option>
                        <option value="Slitting">Slitting</option>
                        <option value="Creasing">Creasing</option>
                        <option value="Both">Both</option>
                        <option value="None">None</option>
                    </select>
                </div>
            </div>

            <!-- Per Piece Special Instructions -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Piece Corrugation Instruction</label>
                    <textarea name="pieces[${pieceIndex}][corrugation_instruction]" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Piece Printing Instruction</label>
                    <textarea name="pieces[${pieceIndex}][printing_instruction]" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Piece Finishing Instruction</label>
                    <textarea name="pieces[${pieceIndex}][finishing_instruction]" class="form-control" rows="2"></textarea>
                </div>
            </div>
            
            <!-- Paper Layers for this piece -->
            <div id="piece${pieceIndex}_layers" class="mt-3" style="display:none;">
                <h6>Paper Structure for Piece ${pieceIndex + 1}</h6>
                <div id="piece${pieceIndex}_layers_container">
                    <!-- Layers will be rendered here -->
                </div>
            </div>
        </div>
    `;
}

function renderPieceLayers(pieceIndex) {
    const plySelect = document.getElementById(`piece${pieceIndex}_ply`);
    const ply = parseInt(plySelect.value);
    const layersDiv = document.getElementById(`piece${pieceIndex}_layers`);
    const container = document.getElementById(`piece${pieceIndex}_layers_container`);
    
    if (!ply) {
        layersDiv.style.display = 'none';
        return;
    }
    
    layersDiv.style.display = 'block';
    container.innerHTML = '';
    
    const layerTypes = {
        3: ['Outer', 'Flute', 'Inner'],
        5: ['Outer', 'Flute', 'Middle', 'Flute', 'Inner'],
        7: ['Outer', 'Flute', 'Middle', 'Flute', 'Middle', 'Flute', 'Inner']
    };
    
    const types = layerTypes[ply];
    types.forEach((type, index) => {
        const layerHtml = `
            <div class="row mb-2">
                <div class="col-md-3">
                    <input type="text" value="${type}" 
                           name="pieces[${pieceIndex}][layers][${index}][type]" 
                           class="form-control form-control-sm" readonly>
                </div>
                <div class="col-md-4">
                    <select name="pieces[${pieceIndex}][layers][${index}][paper_name]" 
                            class="form-control form-control-sm">
                        <option value="">Select Paper</option>
                        @foreach($papers as $paper)
                            <option value="{{ $paper->name }}">{{ $paper->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" 
                           name="pieces[${pieceIndex}][layers][${index}][gsm]" 
                           class="form-control form-control-sm" 
                           placeholder="GSM">
                </div>
                <div class="col-md-3">
                    ${type.includes('Flute') ? `
                        <select name="pieces[${pieceIndex}][layers][${index}][flute_type]" 
                                class="form-control form-control-sm">
                            <option value="">Flute Type</option>
                            <option value="A">A-Flute</option>
                            <option value="B">B-Flute</option>
                            <option value="C">C-Flute</option>
                            <option value="E">E-Flute</option>
                        </select>
                    ` : '<span class="form-control form-control-sm" style="background:#f8f9fa;">-</span>'}
                </div>
            </div>
        `;
        container.innerHTML += layerHtml;
    });
}

function renderPieceInkFields(pieceIndex) {
    const printColors = parseInt(document.getElementById(`piece${pieceIndex}_print_colors`).value);
    const container = document.getElementById(`piece${pieceIndex}_ink_fields`);
    
    container.innerHTML = '';
    
    if (printColors === 0) {
        return; // No printing, no ink fields
    }
    
    for (let i = 0; i < printColors; i++) {
        let options = '<option value="">Select Ink</option>';
        inks.forEach(ink => {
            options += `<option value="${ink.id}">${ink.color_name} (${ink.color_code})</option>`;
        });
        
        const inkHtml = `
            <div class="col-md-${printColors <= 2 ? '6' : '4'} mb-2">
                <label>Ink Color ${i + 1}</label>
                <select name="pieces[${pieceIndex}][printing_data][inks][]" class="form-control form-control-sm">
                    ${options}
                </select>
            </div>
        `;
        container.innerHTML += inkHtml;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    togglePieceMode();
});

</script>
@endsection
