@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ isset($issue) ? 'Edit Job Issue' : 'Issue New Job to Production' }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ isset($issue) ? route('production.update', $issue->id) : route('production.store') }}" method="POST">
                @csrf
                @if(isset($issue))
                    @method('PUT')
                @endif
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Customer</label>
                        <select name="customer_id" id="customer_id" class="form-control" required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ (isset($issue) && $issue->customer_id == $c->id) ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                         <label>Select Job Card</label>
                         <select name="job_card_id" id="job_card_id" class="form-control" required {{ isset($issue) ? '' : 'disabled' }}>
                             <option value="">{{ isset($issue) ? 'Select Job Card' : 'Select Customer First' }}</option>
                             @if(isset($issue))
                                <option value="{{ $issue->jobCard->id }}" selected>{{ $issue->jobCard->job_no }} - {{ $issue->jobCard->item_name }}</option>
                             @endif
                         </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>PO Number</label>
                        <input type="text" name="po_number" class="form-control" value="{{ isset($issue) ? $issue->po_number : '' }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Order Qty (Cartons)</label>
                        <input type="number" name="order_qty_cartons" class="form-control" value="{{ isset($issue) ? $issue->order_qty_cartons : '' }}" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">{{ isset($issue) ? 'Update Job Issue' : 'Issue Job' }}</button>
                @if(isset($issue))
                    <a href="{{ route('production.create') }}" class="btn btn-secondary">Cancel</a>
                @endif
            </form>
        </div>
    </div>

    <!-- Job Issues Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">All Job Issues</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Issue No.</th>
                        <th>Job Card No.</th>
                        <th>Customer</th>
                        <th>PO Number</th>
                        <th>Order Qty</th>
                        <th>Req Sheets</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($issues as $item)
                    <tr>
                        <td><strong>{{ $item->issue_no }}</strong></td>
                        <td>{{ $item->jobCard->job_no }}</td>
                        <td>{{ $item->customer->name }}</td>
                        <td>{{ $item->po_number }}</td>
                        <td>{{ $item->order_qty_cartons }}</td>
                        <td>{{ $item->required_sheet_qty }}</td>
                        <td>
                            <span class="badge bg-{{ $item->status == 'Pending' ? 'secondary' : ($item->status == 'In Progress' ? 'warning' : 'success') }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('production.edit', $item->id) }}" class="btn btn-sm btn-warning shadow-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('production.print', $item->id) }}" class="btn btn-sm btn-dark shadow-sm" title="Print" target="_blank">
                                <i class="fas fa-print"></i>
                            </a>
                            <form action="{{ route('production.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this job issue?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger shadow-sm" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No job issues found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Load Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Select2 JS (loads after jQuery) -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
console.log('=== SCRIPT BLOCK STARTED ===');
console.log('jQuery loaded:', typeof jQuery !== 'undefined');
console.log('$ loaded:', typeof $ !== 'undefined');
console.log('Axios loaded:', typeof axios !== 'undefined');

// Use jQuery ready to ensure DOM is loaded
jQuery(document).ready(function($) {
    console.log('=== DOM Ready ===');
    console.log('Select2 available:', typeof $.fn.select2 !== 'undefined');
    
    if (typeof $.fn.select2 === 'undefined') {
        console.error('Select2 not loaded!');
        alert('Select2 library failed to load. Please refresh the page.');
        return;
    }
    
    console.log('Initializing Job Issue Form...');
    
    // Initialize Select2
    $('#customer_id').select2({ 
        theme: 'bootstrap-5', 
        width: '100%',
        placeholder: 'Select Customer'
    });
    
    $('#job_card_id').select2({ 
        theme: 'bootstrap-5', 
        width: '100%',
        placeholder: 'Select Customer First'
    });
    
    console.log('Select2 initialized successfully');
    
    // Attach change event
    $('#customer_id').on('change', function() {
        var customerId = $(this).val();
        console.log('>>> Customer changed to:', customerId);
        loadJobCards(customerId);
    });
    
    console.log('Event listener attached successfully');
});

function loadJobCards(customerId) {
    console.log('loadJobCards called with ID:', customerId);
    
    var jobSelect = jQuery('#job_card_id');
    
    if (!customerId) {
        console.log('No customer selected, resetting job dropdown');
        jobSelect.html('<option value="">Select Customer First</option>').trigger('change');
        jobSelect.prop('disabled', true);
        return;
    }
    
    jobSelect.html('<option value="">Loading...</option>').trigger('change');
    jobSelect.prop('disabled', true);
    
    var url = '/api/customer/' + customerId + '/jobs?t=' + new Date().getTime();
    console.log('Fetching jobs from:', url);
    
    axios.get(url)
        .then(function(response) {
            console.log('API Response:', response.data);
            var data = response.data;
            
            var options = '<option value="">Select Job Card</option>';
            
            if (data.length === 0) {
                console.log('No jobs found');
                options = '<option value="">No Active Job Cards Found</option>';
            } else {
                console.log('Found ' + data.length + ' jobs');
                data.forEach(function(job) {
                    var label = job.job_no + ' - ' + job.item_name;
                    if (job.item_code) {
                        label += ' (' + job.item_code + ')';
                    }
                    options += '<option value="' + job.id + '">' + label + '</option>';
                });
            }
            
            jobSelect.html(options).prop('disabled', false).trigger('change');
            console.log('Job dropdown updated successfully');
        })
        .catch(function(error) {
            console.error('API Error:', error);
            alert('Failed to load job cards. Error: ' + (error.response ? error.response.data.error : error.message));
            jobSelect.html('<option value="">Error Loading</option>').prop('disabled', false).trigger('change');
        });
}
</script>

@endsection
