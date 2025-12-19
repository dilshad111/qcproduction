@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Job Cards</h2>
        <a href="{{ route('job-cards.create') }}" class="btn btn-primary shadow-sm"><i class="fas fa-plus-circle"></i> Create New Job Card</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <!-- Search and Filter Section -->
            <form method="GET" action="{{ route('job-cards.index') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="text" name="search" class="form-control" placeholder="Search by Job No, Item Name, or Customer" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4 mb-2">
                        <select name="customer_id" class="form-control">
                            <option value="">All Customers</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <button type="submit" class="btn btn-info shadow-sm"><i class="fas fa-search"></i> Search</button>
                        <a href="{{ route('job-cards.index') }}" class="btn btn-secondary shadow-sm"><i class="fas fa-eraser"></i> Clear</a>
                    </div>
                </div>
            </form>

            <!-- Job Cards Table -->
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Job No</th>
                        <th>Customer</th>
                        <th>Item Name</th>
                        <th>Size (L x W x H)</th>
                        <th>Ply</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobCards as $job)
                    <tr>
                        <td>{{ $job->job_no }}</td>
                        <td>{{ $job->customer->name }}</td>
                        <td>{{ $job->item_name }}</td>
                        <td>
                            {{ ($job->uom == 'mm' ? number_format($job->length, 0) : number_format($job->length, 2)) }} x 
                            {{ ($job->uom == 'mm' ? number_format($job->width, 0) : number_format($job->width, 2)) }} x 
                            {{ ($job->uom == 'mm' ? number_format($job->height, 0) : number_format($job->height, 2)) }} 
                            {{ $job->uom }}
                        </td>
                        <td>{{ $job->ply_type }} Ply</td>
                        <td>{{ $job->created_at->format('d-m-Y') }}</td>
                        <td>
                             <a href="{{ route('job-cards.show', $job->id) }}" class="btn btn-sm btn-info shadow-sm text-white" title="View">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('job-cards.print', $job->id) }}" class="btn btn-sm btn-dark shadow-sm" title="Print" target="_blank">
                                <i class="fas fa-print"></i> Print
                            </a>
                            <a href="{{ route('job-cards.edit', $job->id) }}" class="btn btn-sm btn-warning shadow-sm" title="Edit">
                                <i class="fas fa-pencil-alt"></i> Edit
                            </a>
                            <form action="{{ route('job-cards.destroy', $job->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this job card?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger shadow-sm" title="Delete">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No job cards found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
