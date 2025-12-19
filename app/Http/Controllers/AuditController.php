<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    public function index()
    {
        $audits = Audit::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('audits.index', compact('audits'));
    }
}
