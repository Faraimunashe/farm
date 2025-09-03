<?php

namespace App\Http\Controllers\Lender;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $lender = $user->lender;

        $transactions = Transaction::whereHas('farm', function($query) use ($lender) {
            $query->where('user_id', $lender->user_id);
        })->with(['farm', 'farm.user'])
        ->latest()
        ->paginate(15);

        return inertia('Lender/TransactionsPage', [
            'transactions' => $transactions
        ]);
    }

    public function show(Transaction $transaction)
    {
        // Ensure the transaction belongs to a farm from this lender's user
        $user = Auth::user();
        $lender = $user->lender;

        if ($transaction->farm->user_id !== $lender->user_id) {
            abort(403);
        }

        $transaction->load(['farm', 'farm.user']);

        return inertia('Lender/TransactionShowPage', [
            'transaction' => $transaction
        ]);
    }

    public function farmTransactions(Farm $farm)
    {
        // Ensure the farm belongs to a user that this lender can access
        $user = Auth::user();
        $lender = $user->lender;

        if ($farm->user_id !== $lender->user_id) {
            abort(403);
        }

        $transactions = $farm->transactions()
            ->with(['farm.user'])
            ->latest()
            ->paginate(15);

        return inertia('Lender/FarmTransactionsPage', [
            'farm' => $farm,
            'transactions' => $transactions
        ]);
    }

    public function create(Farm $farm)
    {
        // Ensure the farm belongs to a user that this lender can access
        $user = Auth::user();
        $lender = $user->lender;

        if ($farm->user_id !== $lender->user_id) {
            abort(403);
        }

        return inertia('Lender/TransactionCreatePage', [
            'farm' => $farm
        ]);
    }

    public function store(Request $request, Farm $farm)
    {
        // Ensure the farm belongs to a user that this lender can access
        $user = Auth::user();
        $lender = $user->lender;

        if ($farm->user_id !== $lender->user_id) {
            abort(403);
        }

        $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:500',
            'date' => 'required|date'
        ]);

        $transaction = Transaction::create([
            'farm_id' => $farm->id,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date
        ]);

        return redirect()->route('lender.transactions.show', $transaction)
            ->with('success', 'Transaction created successfully.');
    }

    public function edit(Transaction $transaction)
    {
        // Ensure the transaction belongs to a farm from this lender's user
        $user = Auth::user();
        $lender = $user->lender;

        if ($transaction->farm->user_id !== $lender->user_id) {
            abort(403);
        }

        $transaction->load(['farm', 'farm.user']);

        return inertia('Lender/TransactionEditPage', [
            'transaction' => $transaction
        ]);
    }

    public function update(Request $request, Transaction $transaction)
    {
        // Ensure the transaction belongs to a farm from this lender's user
        $user = Auth::user();
        $lender = $user->lender;

        if ($transaction->farm->user_id !== $lender->user_id) {
            abort(403);
        }

        $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:500',
            'date' => 'required|date'
        ]);

        $transaction->update([
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date
        ]);

        return redirect()->route('lender.transactions.show', $transaction)
            ->with('success', 'Transaction updated successfully.');
    }

    public function destroy(Transaction $transaction)
    {
        // Ensure the transaction belongs to a farm from this lender's user
        $user = Auth::user();
        $lender = $user->lender;

        if ($transaction->farm->user_id !== $lender->user_id) {
            abort(403);
        }

        $transaction->delete();

        return redirect()->route('lender.transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }
}
