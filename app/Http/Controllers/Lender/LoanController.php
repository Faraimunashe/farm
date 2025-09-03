<?php

namespace App\Http\Controllers\Lender;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Farm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $lender = $user->lender;

        $loans = Loan::where('lender_id', $lender->id)
            ->with(['user.farms'])
            ->latest()
            ->paginate(15);

        return inertia('Lender/LoansPage', [
            'loans' => $loans
        ]);
    }

    public function show(Loan $loan)
    {
        // Ensure the loan belongs to this lender
        $user = Auth::user();
        $lender = $user->lender;

        if ($loan->lender_id !== $lender->id) {
            abort(403);
        }

        $loan->load(['user.farms']);

        return inertia('Lender/LoanShowPage', [
            'loan' => $loan
        ]);
    }

    public function approve(Loan $loan)
    {
        // Ensure the loan belongs to this lender
        $user = Auth::user();
        $lender = $user->lender;

        if ($loan->lender_id !== $lender->id) {
            abort(403);
        }

        if ($loan->status !== 'pending') {
            return back()->with('error', 'Only pending loans can be approved.');
        }

        $loan->update([
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => $user->id
        ]);

        return redirect()->route('lender.loans.show', $loan->id)->with('success', 'Loan approved successfully.');
    }

    public function reject(Request $request, Loan $loan)
    {
        // Ensure the loan belongs to this lender
        $user = Auth::user();
        $lender = $user->lender;

        if ($loan->lender_id !== $lender->id) {
            abort(403);
        }

        if ($loan->status !== 'pending') {
            return back()->with('error', 'Only pending loans can be rejected.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $loan->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => $user->id,
            'rejection_reason' => $request->rejection_reason
        ]);

        //dd("Rejected loan: " . $request->all());

        return redirect()->route('lender.loans.show', $loan->id)->with('success', 'Loan rejected successfully.');
    }

    public function updateStatus(Request $request, Loan $loan)
    {
        // Ensure the loan belongs to this lender
        $user = Auth::user();
        $lender = $user->lender;

        if ($loan->lender_id !== $lender->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:active,suspended,defaulted,completed'
        ]);

        $loan->update([
            'status' => $request->status,
            'status_updated_at' => now(),
            'status_updated_by' => $user->id
        ]);

        return redirect()->route('lender.loans.show', $loan->id)->with('success', 'Loan status updated successfully.');
    }

    public function applications()
    {
        $user = Auth::user();
        $lender = $user->lender;

        $pendingApplications = Loan::where('lender_id', $lender->id)
            ->where('status', 'pending')
            ->with(['user.farms'])
            ->latest()
            ->paginate(15);

        return inertia('Lender/LoanApplicationsPage', [
            'applications' => $pendingApplications
        ]);
    }

    public function activeLoans()
    {
        $user = Auth::user();
        $lender = $user->lender;

        $activeLoans = Loan::where('lender_id', $lender->id)
            ->where('status', 'active')
            ->with(['user.farms'])
            ->latest()
            ->paginate(15);

        return inertia('Lender/ActiveLoansPage', [
            'loans' => $activeLoans
        ]);
    }
}
