<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PlanFinancial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::whereHas('farm', function($query) {
            $query->where('user_id', Auth::id());
        })
        ->with(['farm', 'financials'])
        ->latest()
        ->get();

        return inertia('Farmer/PlansPage', [
            'plans' => $plans
        ]);
    }

    public function create()
    {
        $farms = \App\Models\Farm::where('user_id', Auth::id())->get();

        return inertia('Farmer/PlanCreatePage', [
            'farms' => $farms
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'description' => 'required|string|max:1000',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'financials' => 'required|array|min:1',
            'financials.*.type' => 'required|in:income,expense',
            'financials.*.description' => 'required|string|max:500',
            'financials.*.amount' => 'required|numeric|min:0',
        ]);

        // Ensure the farm belongs to the authenticated user
        $farm = \App\Models\Farm::where('user_id', Auth::id())->findOrFail($request->farm_id);

        DB::transaction(function () use ($request) {
            $plan = Plan::create([
                'farm_id' => $request->farm_id,
                'description' => $request->description,
                'period_start' => $request->period_start,
                'period_end' => $request->period_end,
            ]);

            foreach ($request->financials as $financial) {
                PlanFinancial::create([
                    'plan_id' => $plan->id,
                    'type' => $financial['type'],
                    'description' => $financial['description'],
                    'amount' => $financial['amount'],
                ]);
            }
        });

        return redirect()->route('farmer.plans.index')->with('success', 'Financial plan created successfully!');
    }

    public function show(Plan $plan)
    {
        // Ensure the plan belongs to a farm owned by the authenticated user
        if ($plan->farm->user_id !== Auth::id()) {
            abort(403);
        }

        $plan->load(['farm', 'financials']);

        // Calculate totals
        $totalIncome = $plan->financials->where('type', 'income')->sum('amount');
        $totalExpenses = $plan->financials->where('type', 'expense')->sum('amount');
        $profit = $totalIncome - $totalExpenses;

        return inertia('Farmer/PlanShowPage', [
            'plan' => $plan,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'profit' => $profit
        ]);
    }

    public function edit(Plan $plan)
    {
        // Ensure the plan belongs to a farm owned by the authenticated user
        if ($plan->farm->user_id !== Auth::id()) {
            abort(403);
        }

        $farms = \App\Models\Farm::where('user_id', Auth::id())->get();
        $plan->load(['farm', 'financials']);

        return inertia('Farmer/PlanEditPage', [
            'plan' => $plan,
            'farms' => $farms
        ]);
    }

    public function update(Request $request, Plan $plan)
    {
        // Ensure the plan belongs to a farm owned by the authenticated user
        if ($plan->farm->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'description' => 'required|string|max:1000',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'financials' => 'required|array|min:1',
            'financials.*.type' => 'required|in:income,expense',
            'financials.*.description' => 'required|string|max:500',
            'financials.*.amount' => 'required|numeric|min:0',
        ]);

        // Ensure the farm belongs to the authenticated user
        $farm = \App\Models\Farm::where('user_id', Auth::id())->findOrFail($request->farm_id);

        DB::transaction(function () use ($request, $plan) {
            $plan->update([
                'farm_id' => $request->farm_id,
                'description' => $request->description,
                'period_start' => $request->period_start,
                'period_end' => $request->period_end,
            ]);

            // Delete existing financials and recreate them
            $plan->financials()->delete();

            foreach ($request->financials as $financial) {
                PlanFinancial::create([
                    'plan_id' => $plan->id,
                    'type' => $financial['type'],
                    'description' => $financial['description'],
                    'amount' => $financial['amount'],
                ]);
            }
        });

        return redirect()->route('farmer.plans.index')->with('success', 'Financial plan updated successfully!');
    }

    public function destroy(Plan $plan)
    {
        // Ensure the plan belongs to a farm owned by the authenticated user
        if ($plan->farm->user_id !== Auth::id()) {
            abort(403);
        }

        $plan->delete();

        return redirect()->route('farmer.plans.index')->with('success', 'Financial plan deleted successfully!');
    }
}
