<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobCard;
use App\Models\ProductionTracking;
use App\Models\Dispatch;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $activeJobsCount = JobCard::count(); // Simplified for now, can be refined based on status
        $todaysProduction = ProductionTracking::whereDate('created_at', Carbon::today())->sum('produced_qty');
        $pendingDispatches = Dispatch::count(); // Revisit if status logic exists

        return view('dashboard', compact('activeJobsCount', 'todaysProduction', 'pendingDispatches'));
    }
}
