@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header pb-0 bg-white border-0">
                    <div class="row">
                        <div class="col-6 d-flex align-items-center">
                            <h4 class="mb-0 font-weight-bolder text-dark">Revision History: {{ $jobCard->job_no }}</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('job-cards.index') }}" class="btn btn-secondary btn-sm mb-0 shadow text-uppercase">
                                <i class="fas fa-arrow-left me-1"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Version</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Revision Note</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Created By</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-center">Date</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($history as $record)
                                <tr class="{{ $record->is_active ? 'bg-light' : '' }}">
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm fw-bold">v {{ $record->version }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($record->is_active)
                                            <span class="badge badge-sm bg-gradient-success">Active</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-secondary">Archived</span>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 text-wrap" style="max-width: 300px;">
                                            {{ $record->revision_note ?: 'Initial Version' }}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">System Admin</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $record->created_at->format('d-m-Y H:i') }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <a href="{{ route('job-cards.show', $record->id) }}" class="btn btn-link text-info p-0 mb-0" title="View This Version">
                                                <i class="fas fa-eye text-lg"></i>
                                            </a>
                                            <a href="{{ route('job-cards.print', $record->id) }}" class="btn btn-link text-dark p-0 mb-0" title="Print This Version" target="_blank">
                                                <i class="fas fa-print text-lg"></i>
                                            </a>
                                            @if(!$record->is_active)
                                            <a href="{{ route('job-cards.revise', $record->id) }}" class="btn btn-link text-success p-0 mb-0" title="Restore & Revise">
                                                <i class="fas fa-rotate text-lg"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
