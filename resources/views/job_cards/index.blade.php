@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Job Cards</h2>
        <a href="{{ route('job-cards.create') }}" class="btn btn-primary">Create New Job Card</a>
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
                        <button type="submit" class="btn btn-info">Search</button>
                        <a href="{{ route('job-cards.index') }}" class="btn btn-secondary">Clear</a>
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
                        <td>{{ $job->length }} x {{ $job->width }} x {{ $job->height }}</td>
                        <td>{{ $job->ply_type }} Ply</td>
                        <td>{{ $job->created_at->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ route('job-cards.show', $job->id) }}" class="btn btn-sm btn-info" title="View">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="{{ route('job-cards.print', $job->id) }}" class="btn btn-sm btn-dark" title="Print" target="_blank">
                                <i class="bi bi-printer"></i> Print
                            </a>
                            <a href="{{ route('job-cards.edit', $job->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('job-cards.destroy', $job->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this job card?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                    <i class="bi bi-trash"></i> Delete
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
