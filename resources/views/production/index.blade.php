@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
         <h2>Production Job Issues</h2>
         <a href="{{ route('production.create') }}" class="btn btn-primary shadow-sm"><i class="fas fa-file-signature"></i> Issue New Job</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Job No</th>
                        <th>PO No</th>
                        <th>Customer</th>
                        <th>Order Qty</th>
                        <th>Req Sheets</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($issues as $issue)
                    <tr>
                        <td>{{ $issue->jobCard->job_no }}</td>
                        <td>{{ $issue->po_number }}</td>
                        <td>{{ $issue->customer->name }}</td>
                        <td>{{ $issue->order_qty_cartons }}</td>
                        <td>
                            @php
                                $ups = $issue->jobCard->ups > 0 ? $issue->jobCard->ups : 1;
                                $reqSheets = ceil($issue->order_qty_cartons / $ups);
                            @endphp
                            {{ $reqSheets }}
                        </td>
                        <td>
                            <span class="badge bg-{{ $issue->status == 'Pending' ? 'warning' : 'info' }}">
                                {{ $issue->status }}
                            </span>
                        </td>
                        <td>{{ $issue->created_at->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ route('production.manage', $issue->id) }}" class="btn btn-sm btn-dark shadow-sm"><i class="fas fa-gears"></i> Manage Production</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
