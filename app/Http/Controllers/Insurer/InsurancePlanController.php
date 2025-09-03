<?php

namespace App\Http\Controllers\Insurer;

use App\Http\Controllers\Controller;
use App\Models\InsurancePlan;
use App\Models\Insurer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InsurancePlanController extends Controller
{
    public function index()
    {
        $insurer = Insurer::where('user_id', Auth::id())->first();

        if (!$insurer) {
            return inertia('Insurer/InsurancePlansPage', [
                'plans' => collect([])
            ]);
        }

        $plans = $insurer->insurancePlans()->withCount('insurances')->latest()->get();

        return inertia('Insurer/InsurancePlansPage', [
            'plans' => $plans
        ]);
    }

    public function create()
    {
        $insurer = Insurer::where('user_id', Auth::id())->first();

        if (!$insurer) {
            return redirect()->route('insurer.profile');
        }

        return inertia('Insurer/InsurancePlanCreatePage');
    }

    public function store(Request $request)
    {
        $insurer = Insurer::where('user_id', Auth::id())->first();

        if (!$insurer) {
            return redirect()->route('insurer.profile');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'coverage_type' => 'required|string|in:crop,livestock,equipment,property',
            'coverage_percentage' => 'required|numeric|min:0|max:100',
            'premium_rate' => 'required|numeric|min:0|max:100',
            'min_coverage' => 'required|numeric|min:0',
            'max_coverage' => 'required|numeric|min:0|gt:min_coverage',
            'duration_months' => 'required|integer|min:1|max:120',
            'deductible_amount' => 'required|numeric|min:0',
            'terms_conditions' => 'nullable|string|max:2000',
            'exclusions' => 'nullable|string|max:2000',
        ]);

        $insurer->insurancePlans()->create([
            'name' => $request->name,
            'description' => $request->description,
            'coverage_type' => $request->coverage_type,
            'coverage_percentage' => $request->coverage_percentage,
            'premium_rate' => $request->premium_rate,
            'min_coverage' => $request->min_coverage,
            'max_coverage' => $request->max_coverage,
            'duration_months' => $request->duration_months,
            'deductible_amount' => $request->deductible_amount,
            'terms_conditions' => $request->terms_conditions,
            'exclusions' => $request->exclusions,
            'is_active' => true,
        ]);

        return redirect()->route('insurer.plans.index')->with('success', 'Insurance plan created successfully!');
    }

    public function show(InsurancePlan $plan)
    {
        $insurer = Insurer::where('user_id', Auth::id())->first();

        if (!$insurer) {
            abort(403);
        }

        if ($plan->insurer_id !== $insurer->id) {
            abort(403);
        }

        $plan->load(['insurer', 'insurances.user', 'insurances.farm']);

        return inertia('Insurer/InsurancePlanShowPage', [
            'plan' => $plan
        ]);
    }

    public function edit(InsurancePlan $plan)
    {
        $insurer = Insurer::where('user_id', Auth::id())->first();

        if (!$insurer) {
            abort(403);
        }

        if ($plan->insurer_id !== $insurer->id) {
            abort(403);
        }

        return inertia('Insurer/InsurancePlanEditPage', [
            'plan' => $plan
        ]);
    }

    public function update(Request $request, InsurancePlan $plan)
    {
        $insurer = Insurer::where('user_id', Auth::id())->first();

        if (!$insurer) {
            abort(403);
        }

        if ($plan->insurer_id !== $insurer->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'coverage_type' => 'required|string|in:crop,livestock,equipment,property',
            'coverage_percentage' => 'required|numeric|min:0|max:100',
            'premium_rate' => 'required|numeric|min:0|max:100',
            'min_coverage' => 'required|numeric|min:0',
            'max_coverage' => 'required|numeric|min:0|gt:min_coverage',
            'duration_months' => 'required|integer|min:1|max:120',
            'deductible_amount' => 'required|numeric|min:0',
            'terms_conditions' => 'nullable|string|max:2000',
            'exclusions' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
        ]);

        $plan->update([
            'name' => $request->name,
            'description' => $request->description,
            'coverage_type' => $request->coverage_type,
            'coverage_percentage' => $request->coverage_percentage,
            'premium_rate' => $request->premium_rate,
            'min_coverage' => $request->min_coverage,
            'max_coverage' => $request->max_coverage,
            'duration_months' => $request->duration_months,
            'deductible_amount' => $request->deductible_amount,
            'terms_conditions' => $request->terms_conditions,
            'exclusions' => $request->exclusions,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('insurer.plans.index')->with('success', 'Insurance plan updated successfully!');
    }

    public function destroy(InsurancePlan $plan)
    {
        $insurer = Insurer::where('user_id', Auth::id())->first();

        if (!$insurer) {
            abort(403);
        }

        if ($plan->insurer_id !== $insurer->id) {
            abort(403);
        }

        $plan->delete();

        return redirect()->route('insurer.plans.index')->with('success', 'Insurance plan deleted successfully!');
    }
}
