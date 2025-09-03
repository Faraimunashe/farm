<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Lender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::where('user_id', Auth::id())
            ->with('lender')
            ->latest()
            ->get();

        return inertia('Farmer/LoansPage', [
            'loans' => $loans
        ]);
    }

    public function create()
    {
        $lenders = Lender::all();

        return inertia('Farmer/LoanCreatePage', [
            'lenders' => $lenders
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'lender_id' => 'required|exists:lenders,id',
            'amount' => 'required|numeric|min:0',
            'purpose' => 'required|string|max:500',
            'term_months' => 'required|integer|min:1',
            'interest_rate' => 'required|numeric|min:0|max:100',
        ]);

        Loan::create([
            'user_id' => Auth::id(),
            'lender_id' => $request->lender_id,
            'amount' => $request->amount,
            'purpose' => $request->purpose,
            'term_months' => $request->term_months,
            'interest_rate' => $request->interest_rate,
            'status' => 'pending',
        ]);

        return redirect()->route('farmer.loans.index')->with('success', 'Loan application submitted successfully!');
    }

    public function show(Loan $loan)
    {
        // Ensure the loan belongs to the authenticated user
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        return inertia('Farmer/LoanShowPage', [
            'loan' => $loan->load('lender')
        ]);
    }

    public function edit(Loan $loan)
    {
        // Ensure the loan belongs to the authenticated user
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow editing if loan is pending
        if ($loan->status !== 'pending') {
            return redirect()->route('farmer.loans.index')->with('error', 'Cannot edit loan that is not pending.');
        }

        $lenders = Lender::all();

        return inertia('Farmer/LoanEditPage', [
            'loan' => $loan,
            'lenders' => $lenders
        ]);
    }

    public function update(Request $request, Loan $loan)
    {
        // Ensure the loan belongs to the authenticated user
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow updating if loan is pending
        if ($loan->status !== 'pending') {
            return redirect()->route('farmer.loans.index')->with('error', 'Cannot update loan that is not pending.');
        }

        $request->validate([
            'lender_id' => 'required|exists:lenders,id',
            'amount' => 'required|numeric|min:0',
            'purpose' => 'required|string|max:500',
            'term_months' => 'required|integer|min:1',
            'interest_rate' => 'required|numeric|min:0|max:100',
        ]);

        $loan->update([
            'lender_id' => $request->lender_id,
            'amount' => $request->amount,
            'purpose' => $request->purpose,
            'term_months' => $request->term_months,
            'interest_rate' => $request->interest_rate,
        ]);

        return redirect()->route('farmer.loans.index')->with('success', 'Loan updated successfully!');
    }

    public function destroy(Loan $loan)
    {
        // Ensure the loan belongs to the authenticated user
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow deletion if loan is pending
        if ($loan->status !== 'pending') {
            return redirect()->route('farmer.loans.index')->with('error', 'Cannot delete loan that is not pending.');
        }

        $loan->delete();

        return redirect()->route('farmer.loans.index')->with('success', 'Loan deleted successfully!');
    }
}
