<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Quote;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_leads' => Lead::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
            'total_clients' => Client::count(),
            'active_deals' => Deal::where('status', 'open')->count(),
            'total_deals_value' => Deal::where('status', 'open')->sum('value'),
            'quotes_pending' => Quote::where('status', 'sent')->count(),
            'payments_this_month' => Payment::where('status', 'completed')
                ->whereMonth('payment_date', now()->month)
                ->sum('amount'),
        ];

        $recent_leads = Lead::with('assignedUser')
            ->latest()
            ->take(5)
            ->get();

        $recent_deals = Deal::with(['client', 'pipelineStage', 'assignedUser'])
            ->where('status', 'open')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recent_leads', 'recent_deals'));
    }
}
