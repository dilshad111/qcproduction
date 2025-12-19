@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Dashboard</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Jobs</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $activeJobsCount }}</h5>
                    <p class="card-text">Total Job Cards in system.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Today's Production</div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($todaysProduction) }}</h5>
                    <p class="card-text">Sheets processed today.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Dispatches</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $pendingDispatches }}</h5>
                    <p class="card-text">Total dispatches recorded.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
