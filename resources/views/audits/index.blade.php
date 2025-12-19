@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">System Audit Logs</h5>
                    <span class="badge bg-primary">{{ $audits->total() }} Total Events</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date & Time</th>
                                    <th>User</th>
                                    <th>Event</th>
                                    <th>Model</th>
                                    <th>Record ID</th>
                                    <th>IP Address</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($audits as $audit)
                                <tr>
                                    <td>{{ $audit->created_at->format('d-M-Y H:i:s') }}</td>
                                    <td>
                                        @if($audit->user)
                                            <span class="fw-bold">{{ $audit->user->name }}</span>
                                            <div class="small text-muted">{{ $audit->user->email }}</div>
                                        @else
                                            <span class="text-muted italic">System / Unknown</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = [
                                                'created' => 'bg-success',
                                                'updated' => 'bg-warning text-dark',
                                                'deleted' => 'bg-danger',
                                                'restored' => 'bg-info',
                                            ][$audit->event] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $badgeClass }} text-uppercase">
                                            {{ $audit->event }}
                                        </span>
                                    </td>
                                    <td>
                                        <code>{{ class_basename($audit->auditable_type) }}</code>
                                    </td>
                                    <td>#{{ $audit->auditable_id }}</td>
                                    <td>{{ $audit->ip_address }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#auditModal{{ $audit->id }}">
                                            View Details
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="auditModal{{ $audit->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Audit Detail #{{ $audit->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <strong>User Agent:</strong><br>
                                                                <small class="text-muted">{{ $audit->user_agent }}</small>
                                                            </div>
                                                            <div class="col-md-6 text-end">
                                                                <strong>URL:</strong><br>
                                                                <small class="text-muted">{{ $audit->url }}</small>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 border-end">
                                                                <h6 class="text-danger fw-bold">Old Values</h6>
                                                                <pre class="bg-light p-2 rounded" style="max-height: 300px; overflow-y: auto;">{{ json_encode($audit->old_values, JSON_PRETTY_PRINT) }}</pre>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6 class="text-success fw-bold">New Values</h6>
                                                                <pre class="bg-light p-2 rounded" style="max-height: 300px; overflow-y: auto;">{{ json_encode($audit->new_values, JSON_PRETTY_PRINT) }}</pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-center">
                        {{ $audits->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
