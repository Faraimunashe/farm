<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::whereHas('farm', function($query) {
            $query->where('user_id', Auth::id());
        })
        ->with('farm')
        ->latest()
        ->paginate(15);

        return inertia('Farmer/TransactionsPage', [
            'transactions' => $transactions
        ]);
    }

    public function create()
    {
        $farms = \App\Models\Farm::where('user_id', Auth::id())->get();

        return inertia('Farmer/TransactionCreatePage', [
            'farms' => $farms
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
            'description' => 'required|string|max:500',
            'date' => 'required|date',
        ]);

        // Ensure the farm belongs to the authenticated user
        $farm = \App\Models\Farm::where('user_id', Auth::id())->findOrFail($request->farm_id);

        Transaction::create([
            'farm_id' => $request->farm_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return redirect()->route('farmer.transactions.index')->with('success', 'Transaction recorded successfully!');
    }

    public function show(Transaction $transaction)
    {
        // Ensure the transaction belongs to a farm owned by the authenticated user
        if ($transaction->farm->user_id !== Auth::id()) {
            abort(403);
        }

        return inertia('Farmer/TransactionShowPage', [
            'transaction' => $transaction->load('farm')
        ]);
    }

    public function edit(Transaction $transaction)
    {
        // Ensure the transaction belongs to a farm owned by the authenticated user
        if ($transaction->farm->user_id !== Auth::id()) {
            abort(403);
        }

        $farms = \App\Models\Farm::where('user_id', Auth::id())->get();

        return inertia('Farmer/TransactionEditPage', [
            'transaction' => $transaction,
            'farms' => $farms
        ]);
    }

    public function update(Request $request, Transaction $transaction)
    {
        // Ensure the transaction belongs to a farm owned by the authenticated user
        if ($transaction->farm->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
            'description' => 'required|string|max:500',
            'date' => 'required|date',
        ]);

        // Ensure the farm belongs to the authenticated user
        $farm = \App\Models\Farm::where('user_id', Auth::id())->findOrFail($request->farm_id);

        $transaction->update([
            'farm_id' => $request->farm_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return redirect()->route('farmer.transactions.index')->with('success', 'Transaction updated successfully!');
    }

    public function destroy(Transaction $transaction)
    {
        // Ensure the transaction belongs to a farm owned by the authenticated user
        if ($transaction->farm->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->delete();

        return redirect()->route('farmer.transactions.index')->with('success', 'Transaction deleted successfully!');
    }
}
