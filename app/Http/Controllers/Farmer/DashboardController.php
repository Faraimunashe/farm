<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Models\Loan;
use App\Models\Transaction;
use App\Models\Insurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get farmer's farms
        $farms = Farm::where('user_id', $user->id)->get();

        // Get recent transactions
        $recentTransactions = Transaction::whereHas('farm', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('farm')->latest()->take(5)->get();

        // Get active loans
        $activeLoans = Loan::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('lender')
            ->get();

        // Get active insurance policies
        $activeInsurance = Insurance::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('insurer')
            ->get();

        // Calculate summary statistics
        $totalFarms = $farms->count();
        $totalFarmSize = $farms->sum('size');
        $totalLoanAmount = $activeLoans->sum('amount');
        $totalInsuranceValue = $activeInsurance->sum('coverage_amount');

        return inertia('Farmer/DashboardPage', [
            'farms' => $farms,
            'recentTransactions' => $recentTransactions,
            'activeLoans' => $activeLoans,
            'activeInsurance' => $activeInsurance,
            'stats' => [
                'totalFarms' => $totalFarms,
                'totalFarmSize' => $totalFarmSize,
                'totalLoanAmount' => $totalLoanAmount,
                'totalInsuranceValue' => $totalInsuranceValue,
            ]
        ]);
    }
}
