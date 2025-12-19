@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Job Card: {{ $jobCard->job_no }}</h2>
    
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

    <form action="{{ route('job-cards.update', $jobCard->id) }}" method="POST" id="jobCardForm">
        @csrf
        @method('PUT')
        
        <!-- BASIC INFO -->
        <div class="card mb-3">
            <div class="card-header">Basic Information</div>
            <div class="card-body">
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
                        <select name="carton_type_id" class="form-control" required>
                            <option value="">Select Type</option>
                            @foreach($cartonTypes as $t)
                                <option value="{{ $t->id }}" {{ old('carton_type_id', $jobCard->carton_type_id) == $t->id ? 'selected' : '' }}>{{ $t->name }} ({{ $t->standard_code }})</option>
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
                        <label>Deckle Size (mm/inch)</label>
                        <input type="number" step="0.01" name="deckle_size" class="form-control" value="{{ old('deckle_size', $jobCard->deckle_size) }}">
                   </div>
                   <div class="col-md-4 mb-3">
                        <label>Sheet Length (inch)</label>
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
                        <select name="ply_type" id="ply_type" class="form-control" required onchange="renderLayers(true)">
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
                        <option value="0" {{ old('print_colors', $jobCard->print_colors) == '0' ? 'selected' : '' }}>Un-Printed</option>
                        <option value="1" {{ old('print_colors', $jobCard->print_colors) == '1' ? 'selected' : '' }}>1-Color</option>
                        <option value="2" {{ old('print_colors', $jobCard->print_colors) == '2' ? 'selected' : '' }}>2-Color</option>
                        <option value="3" {{ old('print_colors', $jobCard->print_colors) == '3' ? 'selected' : '' }}>3-Color</option>
                        <option value="4" {{ old('print_colors', $jobCard->print_colors) == '4' ? 'selected' : '' }}>4-Color</option>
                        <option value="5" {{ old('print_colors', $jobCard->print_colors) == '5' ? 'selected' : '' }}>5-Color</option>
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
                <div id="honeycomb_fields" class="mb-3 p-3 border" style="display:none;">
                    <h5>Honeycomb Details</h5>
                    <textarea name="special_details[honeycomb]" class="form-control" placeholder="Enter Honeycomb Details">{{ $jobCard->special_details['honeycomb'] ?? '' }}</textarea>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="check_separator" onchange="toggleSpecial('separator')" {{ isset($jobCard->special_details['separator']) ? 'checked' : '' }}>
                    <label class="form-check-label">Separator Add-on</label>
                </div>
                <div id="separator_fields" class="mb-3 p-3 border" style="display:none;">
                     <h5>Separator Details</h5>
                     <textarea name="special_details[separator]" class="form-control" placeholder="Enter Separator Details">{{ $jobCard->special_details['separator'] ?? '' }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Packing (Cartons per Bundle)</label>
                    <input type="number" name="packing_bundle_qty" class="form-control" value="{{ old('packing_bundle_qty', $jobCard->packing_bundle_qty) }}">
                </div>
                <div class="mb-3">
                    <label>Remarks</label>
                    <textarea name="remarks" class="form-control">{{ old('remarks', $jobCard->remarks) }}</textarea>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg">Update Job Card</button>
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
    const existingLayers = @json($jobCard->layers);
    const existingPrintingData = @json($jobCard->printing_data);

    // Calculate Deckle Size and Sheet Length
    // Calculate Deckle Size and Sheet Length
    function updateCalculations() {
        const length = parseFloat(document.getElementById('dimension_length').value) || 0;
        const width = parseFloat(document.getElementById('dimension_width').value) || 0;
        const height = parseFloat(document.getElementById('dimension_height').value) || 0;
        const uom = document.getElementById('uom').value;

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

        // Calculate dimensions in MM (assuming standard allowances are in MM)
        // Deckle = Width + Height + 25mm
        const deckle_mm = (w_mm + h_mm) + 25;
        // Sheet = (L*2) + (W*2) + 75mm
        const sheet_length_mm = (l_mm * 2) + (w_mm * 2) + 75;

        // Convert Final Results to INCH
        const deckle_inch = deckle_mm / 25.4;
        const sheet_length_inch = sheet_length_mm / 25.4;

        document.getElementById('deckle_size_calc').value = deckle_inch.toFixed(2);
        document.getElementById('sheet_length_calc').value = sheet_length_inch.toFixed(2);
    }

    document.getElementById('dimension_length').addEventListener('input', updateCalculations);
    document.getElementById('dimension_width').addEventListener('input', updateCalculations);
    document.getElementById('dimension_height').addEventListener('input', updateCalculations);
    
    // Initial Calc
    updateCalculations();

    // Render Layers
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
            // Check if we have existing data for this layer index and current ply matched
            let paperName = '';
            let gsm = '';
            let fluteVal = '';
            
            // Only use existing values if we are NOT resetting (meaning initially loading)
            // And if existingLayers has data for this index
            // And if the ply type matches existing job card's ply type (simple check)
            if (!reset && existingLayers && existingLayers[index]) {
                paperName = existingLayers[index].paper_name;
                gsm = existingLayers[index].gsm;
                fluteVal = existingLayers[index].flute_type;
            }

            let fluteOptions = '';
            if(layer.hasFluteType) {
                const flutes = ['B', 'C', 'E'];
                let opts = '';
                flutes.forEach(f => {
                    const selected = (fluteVal === f) ? 'selected' : '';
                    opts += `<option value="${f}" ${selected}>${f}</option>`;
                });
                
                fluteOptions = `
                    <div class="col-md-2">
                        <label>Flute Type</label>
                        <select name="layers[${index}][flute_type]" class="form-control">
                            ${opts}
                        </select>
                    </div>
                `;
            }

            let html = `
                <div class="row mb-2 border-bottom pb-2">
                    <div class="col-md-3">
                        <strong>${layer.label}</strong>
                        <input type="hidden" name="layers[${index}][type]" value="${layer.type}">
                    </div>
                    <div class="col-md-4">
                        <label>Paper Name</label>
                        <input type="text" name="layers[${index}][paper_name]" class="form-control" value="${paperName}" required>
                    </div>
                    <div class="col-md-2">
                        <label>GSM</label>
                        <input type="number" name="layers[${index}][gsm]" class="form-control" value="${gsm}" required>
                    </div>
                    ${fluteOptions}
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        });
    }

    // Render Ink Fields
    function renderInkFields() {
        const count = document.getElementById('print_colors').value;
        const container = document.getElementById('ink-fields-container');
        container.innerHTML = '';
        
        // Try to get existing ink selections if count matches
        let currentInks = [];
        if (existingPrintingData && existingPrintingData.inks) {
             currentInks = existingPrintingData.inks;
        }

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
        document.getElementById(type + '_fields').style.display = check.checked ? 'block' : 'none';
    }

    // Initial Render
    renderLayers(); 
    renderInkFields();
    toggleStaple(document.querySelector('select[name="pasting_type"]').value);
    toggleSpecial('honeycomb');
    toggleSpecial('separator');


    // Die-Line Generation (Same as create)
    let currentSVG = null;
    function generateDieLine() {
        const length = document.getElementById('dimension_length').value;
        const width = document.getElementById('dimension_width').value;
        const height = document.getElementById('dimension_height').value;
        const cartonTypeSelect = document.querySelector('select[name="carton_type_id"]');
        const selectedOption = cartonTypeSelect.options[cartonTypeSelect.selectedIndex];
        const fefcoCode = selectedOption ? selectedOption.text.match(/\(([^)]+)\)/)?.[1] : '0201';

        if (!length || !width || !height) {
            alert('Please enter Length, Width, and Height dimensions first.');
            return;
        }

        const modalElement = document.getElementById('dieLineModal');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        
        document.getElementById('dieline-loading').style.display = 'block';
        document.getElementById('dieline-container').innerHTML = '';

        const itemName = document.querySelector('input[name="item_name"]').value;

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
        const svgData = new XMLSerializer().serializeToString(svgElement);
        const canvas = document.getElementById('dieline-canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();
        const blob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });
        const url = URL.createObjectURL(blob);

        img.onload = function() {
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0);
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
