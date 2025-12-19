@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Issue New Job to Production</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('production.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Customer</label>
                        <select name="customer_id" id="customer_id" class="form-control" required onchange="loadJobs()">
                            <option value="">Select Customer</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                         <label>Select Job Card</label>
                         <select name="job_card_id" id="job_card_id" class="form-control" required disabled onchange="checkJobDetails()">
                             <option value="">Select Customer First</option>
                         </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>PO Number</label>
                        <input type="text" name="po_number" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Order Qty (Cartons)</label>
                        <input type="number" name="order_qty_cartons" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Issue Job</button>
            </form>
        </div>
    </div>
</div>

<script>
function loadJobs() {
    const customerId = document.getElementById('customer_id').value;
    const jobSelect = document.getElementById('job_card_id');
    
    jobSelect.innerHTML = '<option value="">Loading...</option>';
    jobSelect.disabled = true;

    if(!customerId) {
        jobSelect.innerHTML = '<option value="">Select Customer First</option>';
        return;
    }

    fetch(`/api/customer/${customerId}/jobs`)
        .then(response => response.json())
        .then(data => {
            jobSelect.innerHTML = '<option value="">Select Job Card</option>';
            data.forEach(job => {
                jobSelect.innerHTML += `<option value="${job.id}">
                    ${job.job_no} - ${job.item_name} (${job.item_code || ''})
                </option>`;
            });
            jobSelect.disabled = false;
        });
}

function checkJobDetails() {
    // Placeholder for future logic if we want to show job details on selection
}
</script>
@endsection
