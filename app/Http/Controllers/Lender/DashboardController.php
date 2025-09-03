<?php

namespace App\Http\Controllers\Lender;

use App\Http\Controllers\Controller;
use App\Models\Lender;
use App\Models\Loan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $lender = Lender::where('user_id', $user->id)->first();

        // Get all loan applications
        $loanApplications = Loan::with(['user.farms'])
            ->where('lender_id', $lender->id)
            ->latest()
            ->take(10)
            ->get();

        // Get active loans
        $activeLoans = Loan::where('lender_id', $lender->id)
            ->where('status', 'active')
            ->with(['user.farms'])
            ->get();

        // Get pending loan applications
        $pendingLoans = Loan::where('lender_id', $lender->id)
            ->where('status', 'pending')
            ->with(['user.farms'])
            ->get();

        // Get recent transactions (farm transactions only)
        $recentTransactions = Transaction::whereHas('farm', function($query) use ($lender) {
            $query->where('user_id', $lender->user_id);
        })->with(['farm', 'farm.user'])->latest()->take(5)->get();

        // Calculate summary statistics
        $totalLoans = Loan::where('lender_id', $lender->id)->count();
        $activeLoanCount = $activeLoans->count();
        $pendingLoanCount = $pendingLoans->count();
        $totalLoanAmount = $activeLoans->sum('amount');
        $totalOutstandingAmount = $activeLoans->sum('outstanding_amount');

        return inertia('Lender/DashboardPage', [
            'loanApplications' => $loanApplications,
            'activeLoans' => $activeLoans,
            'pendingLoans' => $pendingLoans,
            'recentTransactions' => $recentTransactions,
            'stats' => [
                'totalLoans' => $totalLoans,
                'activeLoanCount' => $activeLoanCount,
                'pendingLoanCount' => $pendingLoanCount,
                'totalLoanAmount' => $totalLoanAmount,
                'totalOutstandingAmount' => $totalOutstandingAmount,
            ]
        ]);
    }
}
