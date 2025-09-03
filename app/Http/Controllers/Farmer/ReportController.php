<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Models\Loan;
use App\Models\Transaction;
use App\Models\Insurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get summary statistics
        $farms = Farm::where('user_id', $user->id)->get();
        $loans = Loan::where('user_id', $user->id)->get();
        $insurance = Insurance::where('user_id', $user->id)->get();

        // Calculate totals
        $totalFarms = $farms->count();
        $totalFarmSize = $farms->sum('size');
        $totalLoanAmount = $loans->sum('amount');
        $totalInsuranceValue = $insurance->sum('coverage_amount');

        // Get monthly transaction data for the last 12 months
        $monthlyTransactions = Transaction::whereHas('farm', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(amount) as total')
        ->where('created_at', '>=', now()->subMonths(12))
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        // Get farm type distribution
        $farmTypeDistribution = $farms->groupBy('type')
            ->map(function($group) {
                return $group->count();
            });

        // Get loan status distribution
        $loanStatusDistribution = $loans->groupBy('status')
            ->map(function($group) {
                return $group->count();
            });

        return inertia('Farmer/ReportsPage', [
            'stats' => [
                'totalFarms' => $totalFarms,
                'totalFarmSize' => $totalFarmSize,
                'totalLoanAmount' => $totalLoanAmount,
                'totalInsuranceValue' => $totalInsuranceValue,
            ],
            'monthlyTransactions' => $monthlyTransactions,
            'farmTypeDistribution' => $farmTypeDistribution,
            'loanStatusDistribution' => $loanStatusDistribution,
        ]);
    }
}
