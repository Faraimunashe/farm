<?php

namespace App\Http\Controllers\Insurer;

use App\Http\Controllers\Controller;
use App\Models\Insurer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InsurerController extends Controller
{
    public function index()
    {
        $insurer = Insurer::where('user_id', Auth::id())->first();

        if (!$insurer) {
            return inertia('Insurer/DashboardPage', [
                'insurer' => null,
                'stats' => [
                    'totalPlans' => 0,
                    'totalPolicies' => 0,
                    'totalRevenue' => 0,
                    'activePolicies' => 0,
                    'pendingPolicies' => 0,
                    'totalClaims' => 0,
                    'pendingClaims' => 0,
                    'totalPayments' => 0,
                    'pendingPayments' => 0,
                    'failedPayments' => 0,
                ]
            ]);
        }

        // Get statistics
        $totalPlans = $insurer->insurancePlans()->count();
        $activePlans = $insurer->activeInsurancePlans()->count();

        $totalPolicies = $insurer->insurances()->count();
        $activePolicies = $insurer->activeInsurances()->count();
        $pendingPolicies = $insurer->insurances()->where('status', 'pending')->count();

        $totalRevenue = $insurer->insurances()->where('payment_status', 'paid')->sum('premium_amount');
        $pendingRevenue = $insurer->insurances()->where('payment_status', 'pending')->sum('premium_amount');

        $totalClaims = $insurer->insurances()->withCount('claims')->get()->sum('claims_count');
        $pendingClaims = $insurer->insurances()->withCount(['claims' => function($query) {
            $query->where('status', 'pending');
        }])->get()->sum('claims_count');

        $totalPayments = $insurer->insurances()->withCount(['payments' => function($query) {
            $query->where('status', 'completed');
        }])->get()->sum('payments_count');
        $pendingPayments = $insurer->insurances()->withCount(['payments' => function($query) {
            $query->where('status', 'pending');
        }])->get()->sum('payments_count');
        $failedPayments = $insurer->insurances()->withCount(['payments' => function($query) {
            $query->where('status', 'failed');
        }])->get()->sum('payments_count');

        return inertia('Insurer/DashboardPage', [
            'insurer' => $insurer,
            'stats' => [
                'totalPlans' => $totalPlans,
                'activePlans' => $activePlans,
                'totalPolicies' => $totalPolicies,
                'activePolicies' => $activePolicies,
                'pendingPolicies' => $pendingPolicies,
                'totalRevenue' => $totalRevenue,
                'pendingRevenue' => $pendingRevenue,
                'totalClaims' => $totalClaims,
                'pendingClaims' => $pendingClaims,
                'totalPayments' => $totalPayments,
                'pendingPayments' => $pendingPayments,
                'failedPayments' => $failedPayments,
            ]
        ]);
    }

    public function profile()
    {
        $insurer = Insurer::where('user_id', Auth::id())->first();

        return inertia('Insurer/ProfilePage', [
            'insurer' => $insurer
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'contact' => 'required|string|max:255',
        ]);

        $insurer = Insurer::where('user_id', Auth::id())->first();

        if (!$insurer) {
            // Create new insurer profile
            $insurer = Insurer::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'address' => $request->address,
                'contact' => $request->contact,
            ]);
        } else {
            // Update existing profile
            $insurer->update([
                'name' => $request->name,
                'address' => $request->address,
                'contact' => $request->contact,
            ]);
        }

        return redirect()->route('insurer.dashboard')->with('success', 'Profile updated successfully!');
    }
}
