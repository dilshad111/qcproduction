@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header pb-0 bg-white border-0">
                    <div class="row">
                        <div class="col-6 d-flex align-items-center">
                            <h4 class="mb-0 font-weight-bolder text-dark">Job Cards</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('job-cards.create') }}" class="btn btn-primary btn-sm mb-0 shadow text-uppercase">
                                <i class="fas fa-plus-circle me-1"></i> Create New Job Card
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="p-4 border-bottom mb-3 bg-light-soft">
                        <form method="GET" action="{{ route('job-cards.index') }}">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search No., Item, Customer..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <select name="customer_id" class="form-select shadow-none">
                                        <option value="">All Customers</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex gap-2">
                                    <button type="submit" class="btn btn-info btn-sm mb-0 flex-grow-1"><i class="fas fa-filter"></i> Search</button>
                                    <a href="{{ route('job-cards.index') }}" class="btn btn-secondary btn-sm mb-0"><i class="fas fa-undo text-xs"></i></a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Job Card No.</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Customer</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Item Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-center">Size (L x W x H)</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-center">Ply</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-center">Version</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-center">Created At</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jobCards as $job)
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm fw-bold text-primary">{{ $job->job_no }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $job->customer->name }}</p>
                                    </td>
                                    <td>
                                        <span class="text-xs font-weight-bold">{{ \Illuminate\Support\Str::limit($job->item_name, 30) }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-light text-dark border">
                                            {{ ($job->uom == 'mm' ? number_format($job->length, 0) : number_format($job->length, 2)) }} x 
                                            {{ ($job->uom == 'mm' ? number_format($job->width, 0) : number_format($job->width, 2)) }} x 
                                            {{ ($job->uom == 'mm' ? number_format($job->height, 0) : number_format($job->height, 2)) }} 
                                            {{ $job->uom }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-sm bg-gradient-secondary">{{ $job->ply_type }} Ply</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-sm bg-gradient-info">v {{ $job->version }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $job->created_at->format('d-m-Y') }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                            <a href="{{ route('job-cards.show', $job->id) }}" class="btn btn-link text-info p-0 mb-0" title="View">
                                                <i class="fas fa-eye text-lg"></i>
                                            </a>
                                            <a href="{{ route('job-cards.print', $job->id) }}" class="btn btn-link text-dark p-0 mb-0" title="Print" target="_blank">
                                                <i class="fas fa-print text-lg"></i>
                                            </a>
                                            <a href="{{ route('job-cards.edit', $job->id) }}" class="btn btn-link text-warning p-0 mb-0" title="Edit Current Version">
                                                <i class="fas fa-edit text-lg"></i>
                                            </a>
                                            <a href="{{ route('job-cards.revise', $job->id) }}" class="btn btn-link text-success p-0 mb-0" title="Revise (New Version)">
                                                <i class="fas fa-file-signature text-lg"></i>
                                            </a>
                                            <a href="{{ route('job-cards.history', $job->id) }}" class="btn btn-link text-primary p-0 mb-0" title="Revision History">
                                                <i class="fas fa-history text-lg"></i>
                                            </a>
                                            <form action="{{ route('job-cards.destroy', $job->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this job card?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0 mb-0" title="Delete">
                                                    <i class="fas fa-trash-alt text-lg"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <p class="text-secondary mb-0">No job cards found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
