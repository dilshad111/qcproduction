@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Job Card Details: {{ $jobCard->job_no }}</h2>
        <div>
            <a href="{{ route('job-cards.edit', $jobCard->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('job-cards.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <!-- Basic Information -->
    <div class="card mb-3">
        <div class="card-header bg-light">Basic Information</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="text-muted">Customer</label>
                    <p class="font-weight-bold">{{ $jobCard->customer->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="text-muted">Carton Type</label>
                    <p class="font-weight-bold">{{ $jobCard->cartonType->name ?? 'N/A' }} ({{ $jobCard->cartonType->standard_code ?? '' }})</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="text-muted">Item Name</label>
                    <p class="font-weight-bold">{{ $jobCard->item_name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="text-muted">Item Code</label>
                    <p class="font-weight-bold">{{ $jobCard->item_code }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="text-muted">Job Date</label>
                    <p class="font-weight-bold">{{ $jobCard->created_at->format('d M Y') }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="text-muted">Status</label>
                    <span class="badge bg-{{ $jobCard->status == 'Completed' ? 'success' : 'primary' }}">{{ $jobCard->status ?? 'Draft' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Dimensions & Calculations -->
    <div class="card mb-3">
        <div class="card-header bg-light">Dimensions & Technical Specs</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label class="text-muted">Dimensions (L x W x H)</label>
                    <p class="font-weight-bold">{{ $jobCard->length }} x {{ $jobCard->width }} x {{ $jobCard->height }} mm</p>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">Deckle Size</label>
                    <p class="font-weight-bold">{{ $jobCard->deckle_size }} mm</p>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">Sheet Length</label>
                    <p class="font-weight-bold">{{ $jobCard->sheet_length }} inch</p>
                </div>
                <div class="col-md-3">
                    <label class="text-muted">UPS</label>
                    <p class="font-weight-bold">{{ $jobCard->ups }}</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4">
                    <label class="text-muted">Ply Type</label>
                    <p class="font-weight-bold">{{ $jobCard->ply_type }}-Ply</p>
                </div>
                <div class="col-md-4">
                    <label class="text-muted">Slitting Creasing</label>
                    <p class="font-weight-bold">{{ $jobCard->slitting_creasing ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Paper Layers -->
    @if($jobCard->layers->count() > 0)
    <div class="card mb-3">
        <div class="card-header bg-light">Paper Structure</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Layer Type</th>
                        <th>Paper Name</th>
                        <th>GSM</th>
                        <th>Flute Type</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobCard->layers as $layer)
                    <tr>
                        <td>{{ ucfirst($layer->type) }}</td>
                        <td>{{ $layer->paper_name }}</td>
                        <td>{{ $layer->gsm }}</td>
                        <td>{{ $layer->flute_type ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Printing & Finishing -->
    <div class="card mb-3">
        <div class="card-header bg-light">Printing & Finishing</div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="text-muted">Printing Colors</label>
                    <p class="font-weight-bold">{{ $jobCard->print_colors }} Color(s)</p>
                </div>
                @if(!empty($jobCard->printing_data) && isset($jobCard->printing_data['inks']))
                    <div class="col-md-8">
                        <label class="text-muted">Inks Used</label>
                        <p>
                            @foreach($jobCard->printing_data['inks'] as $inkId)
                                @php $ink = \App\Models\Ink::find($inkId); @endphp
                                <span class="badge bg-secondary me-1">{{ $ink ? $ink->color_name : 'Unknown Ink' }}</span>
                            @endforeach
                        </p>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label class="text-muted">Pasting</label>
                    <p class="font-weight-bold">{{ $jobCard->pasting_type }} {{ $jobCard->pasting_type == 'Staple' ? '('.$jobCard->staple_details.')' : '' }}</p>
                </div>
                <div class="col-md-4">
                    <label class="text-muted">Process Type</label>
                    <p class="font-weight-bold">{{ $jobCard->process_type }}</p>
                </div>
            </div>
            @if(!empty($jobCard->special_details))
                <div class="row mt-3 border-top pt-3">
                     @if(isset($jobCard->special_details['honeycomb']))
                        <div class="col-md-6">
                            <label class="text-muted">Honeycomb Details</label>
                            <p>{{ $jobCard->special_details['honeycomb'] }}</p>
                        </div>
                     @endif
                     @if(isset($jobCard->special_details['separator']))
                        <div class="col-md-6">
                            <label class="text-muted">Separator Details</label>
                            <p>{{ $jobCard->special_details['separator'] }}</p>
                        </div>
                     @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Other Details -->
    <div class="card mb-3">
        <div class="card-header bg-light">Instruction & Remarks</div>
        <div class="card-body">
            <div class="row">
                 <div class="col-md-6">
                    <label class="text-muted">Packing (Cartons/Bundle)</label>
                    <p class="font-weight-bold">{{ $jobCard->packing_bundle_qty }}</p>
                 </div>
                 <div class="col-md-6">
                    <label class="text-muted">Remarks</label>
                    <p>{{ $jobCard->remarks }}</p>
                 </div>
            </div>
        </div>
    </div>
</div>
@endsection
