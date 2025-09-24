<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'active_subscriptions' => Subscription::where('user_id', $user->id)->where('stripe_status', 'active')->count(),
            'total_invoices' => $user->invoices()->count(),
            'total_revenue' => $user->invoices()->sum('total') / 100,
        ]);
    }
}
