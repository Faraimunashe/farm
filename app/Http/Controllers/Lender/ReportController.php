<?php

namespace App\Http\Controllers\Lender;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $lender = $user->lender;

        // Get basic statistics
        $totalLoans = Loan::where('lender_id', $lender->id)->count();
        $activeLoans = Loan::where('lender_id', $lender->id)->where('status', 'active')->count();
        $pendingLoans = Loan::where('lender_id', $lender->id)->where('status', 'pending')->count();
        $defaultedLoans = Loan::where('lender_id', $lender->id)->where('status', 'defaulted')->count();

        // Get loan amounts by status
        $loanAmountsByStatus = Loan::where('lender_id', $lender->id)
            ->select('status', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // Get monthly farm transactions for the current year
        $monthlyTransactions = Transaction::whereHas('farm', function($query) use ($lender) {
            $query->where('user_id', $lender->user_id);
        })
        ->whereYear('date', date('Y'))
        ->select(DB::raw('MONTH(date) as month'), DB::raw('SUM(amount) as total_amount'))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

                // Get loan performance by farm size
        $loansByFarmSize = Loan::where('lender_id', $lender->id)
            ->with(['user.farms'])
            ->get()
            ->groupBy(function($loan) {
                $farm = $loan->user->farms->first();
                if (!$farm) return 'Unknown';

                if ($farm->size < 10) return 'Small (< 10 acres)';
                if ($farm->size <= 50) return 'Medium (10-50 acres)';
                return 'Large (> 50 acres)';
            })
            ->map(function($loans) {
                return [
                    'loan_count' => $loans->count(),
                    'avg_loan_amount' => $loans->avg('amount'),
                    'defaulted_count' => $loans->where('status', 'defaulted')->count()
                ];
            });

        return inertia('Lender/ReportsPage', [
            'stats' => [
                'totalLoans' => $totalLoans,
                'activeLoans' => $activeLoans,
                'pendingLoans' => $pendingLoans,
                'defaultedLoans' => $defaultedLoans,
            ],
            'loanAmountsByStatus' => $loanAmountsByStatus,
            'monthlyTransactions' => $monthlyTransactions,
            'loansByFarmSize' => $loansByFarmSize,
        ]);
    }

    public function loanPerformance()
    {
        $user = Auth::user();
        $lender = $user->lender;

        // Get loan performance metrics
        $loanPerformance = Loan::where('lender_id', $lender->id)
            ->select(
                'status',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('AVG(amount) as avg_amount'),
                DB::raw('MIN(amount) as min_amount'),
                DB::raw('MAX(amount) as max_amount')
            )
            ->groupBy('status')
            ->get();

        // Get loans by purpose
        $loansByPurpose = Loan::where('lender_id', $lender->id)
            ->select('purpose', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total_amount'))
            ->groupBy('purpose')
            ->get();

        // Get default rate by loan amount range
        $defaultRateByAmount = Loan::where('lender_id', $lender->id)
            ->select(
                DB::raw('CASE
                    WHEN amount < 10000 THEN "Small (< $10k)"
                    WHEN amount BETWEEN 10000 AND 50000 THEN "Medium ($10k-$50k)"
                    ELSE "Large (> $50k)"
                END as amount_range'),
                DB::raw('COUNT(*) as total_loans'),
                DB::raw('SUM(CASE WHEN status = "defaulted" THEN 1 ELSE 0 END) as defaulted_loans'),
                DB::raw('ROUND((SUM(CASE WHEN status = "defaulted" THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as default_rate')
            )
            ->groupBy('amount_range')
            ->get();

        return inertia('Lender/LoanPerformancePage', [
            'loanPerformance' => $loanPerformance,
            'loansByPurpose' => $loansByPurpose,
            'defaultRateByAmount' => $defaultRateByAmount,
        ]);
    }

    public function financialSummary()
    {
        $user = Auth::user();
        $lender = $user->lender;

        // Get current year financial summary
        $currentYear = date('Y');

        $financialSummary = [
            'totalIncome' => Transaction::whereHas('farm', function($query) use ($lender) {
                $query->where('user_id', $lender->user_id);
            })
            ->where('type', 'income')
            ->whereYear('date', $currentYear)
            ->sum('amount'),

            'totalExpenses' => Transaction::whereHas('farm', function($query) use ($lender) {
                $query->where('user_id', $lender->user_id);
            })
            ->where('type', 'expense')
            ->whereYear('date', $currentYear)
            ->sum('amount'),
        ];

        // Get outstanding loan amounts
        $outstandingLoans = Loan::where('lender_id', $lender->id)
            ->where('status', 'active')
            ->select(
                DB::raw('SUM(amount) as total_outstanding'),
                DB::raw('COUNT(*) as active_loan_count')
            )
            ->first();

        $financialSummary['totalOutstanding'] = $outstandingLoans->total_outstanding ?? 0;
        $financialSummary['activeLoanCount'] = $outstandingLoans->active_loan_count ?? 0;

        // Get monthly cash flow for the current year
        $monthlyCashFlow = DB::table('transactions')
            ->join('farms', 'transactions.farm_id', '=', 'farms.id')
            ->where('farms.user_id', $lender->user_id)
            ->whereYear('transactions.date', $currentYear)
            ->select(
                DB::raw('MONTH(transactions.date) as month'),
                DB::raw('SUM(CASE WHEN transactions.type = "income" THEN transactions.amount ELSE 0 END) as income'),
                DB::raw('SUM(CASE WHEN transactions.type = "expense" THEN transactions.amount ELSE 0 END) as expenses')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return inertia('Lender/FinancialSummaryPage', [
            'financialSummary' => $financialSummary,
            'monthlyCashFlow' => $monthlyCashFlow,
            'currentYear' => $currentYear,
        ]);
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        $lender = $user->lender;

        $request->validate([
            'report_type' => 'required|in:loan_summary,financial_summary,loan_performance',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'format' => 'required|in:csv,pdf'
        ]);

        // Implementation for export functionality would go here
        // This could generate CSV or PDF reports based on the parameters

        return back()->with('success', 'Report export initiated. You will receive a notification when ready.');
    }
}
