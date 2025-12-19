@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Corrugation Plant Jobs</h2>
        <a href="{{ route('corrugation.report') }}" class="btn btn-secondary shadow-sm"><i class="fas fa-file-alt"></i> Daily Report</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Job No</th>
                        <th>Customer</th>
                        <th>Item</th>
                        <th>Order Qty (Sheets)</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobIssues as $issue)
                    <tr>
                        <td>{{ $issue->jobCard->job_no }}</td>
                        <td>{{ $issue->customer->name }}</td>
                        <td>{{ $issue->jobCard->item_name }}</td>
                        <td>{{ ceil($issue->order_qty_cartons / ($issue->jobCard->ups > 0 ? $issue->jobCard->ups : 1)) }}</td>
                        <td>
                            @php
                                // Check if started in corrugation logs
                                $started = \App\Models\CorrugationLog::where('job_issue_id', $issue->id)->exists();
                            @endphp
                            <span class="badge bg-{{ $started ? 'warning' : 'secondary' }}">
                                {{ $started ? 'In Progress' : 'Pending' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('corrugation.manage', $issue->id) }}" class="btn btn-sm btn-primary shadow-sm">
                                <i class="fas fa-cogs"></i> Manage
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No pending jobs found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
