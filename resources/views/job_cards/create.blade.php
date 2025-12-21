@extends('layouts.app')

@section('content')
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
                    <div class="col-md-4 mb-3">
                        <label>Ply Type</label>
                        <select name="ply_type" id="ply_type" class="form-control" required onchange="renderLayers()">
                            <option value="">Select Ply</option>
                            <option value="3" {{ old('ply_type') == '3' ? 'selected' : '' }}>3-Ply</option>
                            <option value="5" {{ old('ply_type') == '5' ? 'selected' : '' }}>5-Ply</option>
                            <option value="7" {{ old('ply_type') == '7' ? 'selected' : '' }}>7-Ply</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
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

        <!-- PAPER STRUCTURE LOGIC -->
        <div class="card mb-3" id="paper-structure-card" style="display:none;">
            <div class="card-header">Paper Structure Configuration</div>
            <div class="card-body" id="layers-container">
                <!-- Javascript will render rows here -->
            </div>
        </div>

        <!-- PRINTING CONFIG -->
        <div class="card mb-3">
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
                <div id="honeycomb_fields" class="mb-3 p-3 border" style="display:none;">
                    <h5>Honeycomb Details</h5>
                    <!-- Simple JSON store for now, can be expanded to fields -->
                    <textarea name="special_details[honeycomb]" class="form-control" placeholder="Enter Honeycomb Details"></textarea>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_separator" onchange="toggleSpecial('separator')">
                    <label class="form-check-label">Separator Add-on</label>
                </div>
                <div id="separator_fields" class="mb-3 p-3 border" style="display:none;">
                     <h5>Separator Details</h5>
                     <textarea name="special_details[separator]" class="form-control" placeholder="Enter Separator Details"></textarea>
                </div>

                <div class="mb-3">
                    <label>Packing (Cartons per Bundle)</label>
                    <input type="number" name="packing_bundle_qty" class="form-control">
                </div>
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
    document.getElementById(type + '_fields').style.display = check.checked ? 'block' : 'none';
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
    const fefcoCode = selectedOption ? selectedOption.text.match(/\(([^)]+)\)/)?.[1] : '0201';

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

</script>
@endsection
