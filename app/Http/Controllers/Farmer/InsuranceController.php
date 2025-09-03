<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Insurance;
use App\Models\InsurancePlan;
use App\Models\Insurer;
use App\Models\Farm;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InsuranceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $insurances = Insurance::where('user_id', $user->id)
            ->with(['insurer', 'insurancePlan', 'farm'])
            ->latest()
            ->get();

        return inertia('Farmer/InsurancePage', [
            'insurances' => $insurances
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        $farms = Farm::where('user_id', $user->id)->get();
        $insurers = Insurer::with(['activeInsurancePlans'])->get();

        return inertia('Farmer/InsuranceCreatePage', [
            'farms' => $farms,
            'insurers' => $insurers
        ]);
    }

    public function getPlans(Request $request)
    {
        $request->validate([
            'insurer_id' => 'required|exists:insurers,id',
            'farm_id' => 'required|exists:farms,id',
            'coverage_type' => 'nullable|string|in:crop,livestock,equipment,property'
        ]);

        $insurer = Insurer::findOrFail($request->insurer_id);
        $farm = Farm::findOrFail($request->farm_id);

        \Log::info('Loading plans for insurer: ' . $insurer->id . ' and farm: ' . $farm->id);

        $query = $insurer->activeInsurancePlans();

        if ($request->coverage_type) {
            $query->where('coverage_type', $request->coverage_type);
        }

        $plans = $query->get();

        \Log::info('Found ' . $plans->count() . ' plans for insurer');

        // Calculate coverage amounts based on farm value
        $farmValue = $farm->estimated_value ?? 10000; // Default value if not set

        foreach ($plans as $plan) {
            $plan->calculated_coverage = min(
                max($plan->min_coverage, $farmValue * ($plan->coverage_percentage / 100)),
                $plan->max_coverage
            );
            $plan->calculated_premium = $plan->calculated_coverage * ($plan->premium_rate / 100);
        }

        $response = [
            'plans' => $plans,
            'farm' => $farm
        ];

        \Log::info('Returning response with ' . count($plans) . ' plans');

        return response()->json($response);
    }

    public function store(Request $request)
    {
        $request->validate([
            'insurer_id' => 'required|exists:insurers,id',
            'insurance_plan_id' => 'required|exists:insurance_plans,id',
            'farm_id' => 'required|exists:farms,id',
            'coverage_amount' => 'required|numeric|min:0',
            'premium_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:paynow,ecocash,bank_transfer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        try {
            DB::beginTransaction();

            // Create the insurance policy
            $insurance = Insurance::create([
                'user_id' => Auth::id(),
                'insurer_id' => $request->insurer_id,
                'insurance_plan_id' => $request->insurance_plan_id,
                'farm_id' => $request->farm_id,
                'policy_number' => 'POL-' . time() . '-' . Auth::id(),
                'coverage_amount' => $request->coverage_amount,
                'premium_amount' => $request->premium_amount,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
            ]);

            // Create initial payment record
            $payment = Payment::create([
                'user_id' => Auth::id(),
                'insurance_id' => $insurance->id,
                'amount' => $request->premium_amount,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'currency' => 'USD',
                'description' => 'Insurance premium payment for ' . $insurance->policy_number,
            ]);

            DB::commit();

            return redirect()->route('farmer.insurance.show', $insurance)
                ->with('success', 'Insurance application submitted successfully! Payment is pending.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create insurance policy. Please try again.']);
        }
    }

    public function show(Insurance $insurance)
    {
        // Ensure the insurance belongs to the authenticated user
        if ($insurance->user_id !== Auth::id()) {
            abort(403);
        }

        $insurance->load(['insurer', 'insurancePlan', 'farm', 'payments', 'claims']);

        return inertia('Farmer/InsuranceShowPage', [
            'insurance' => $insurance
        ]);
    }

    public function edit(Insurance $insurance)
    {
        // Ensure the insurance belongs to the authenticated user
        if ($insurance->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow editing if status is pending
        if ($insurance->status !== 'pending') {
            return redirect()->route('farmer.insurance.show', $insurance)
                ->with('error', 'Cannot edit active or expired insurance policies.');
        }

        $user = Auth::user();
        $farms = Farm::where('user_id', $user->id)->get();
        $insurers = Insurer::with(['activeInsurancePlans'])->get();

        return inertia('Farmer/InsuranceEditPage', [
            'insurance' => $insurance,
            'farms' => $farms,
            'insurers' => $insurance->insurer->activeInsurancePlans
        ]);
    }

    public function update(Request $request, Insurance $insurance)
    {
        // Ensure the insurance belongs to the authenticated user
        if ($insurance->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow updating if status is pending
        if ($insurance->status !== 'pending') {
            return redirect()->route('farmer.insurance.show', $insurance)
                ->with('error', 'Cannot update active or expired insurance policies.');
        }

        $request->validate([
            'insurer_id' => 'required|exists:insurers,id',
            'insurance_plan_id' => 'required|exists:insurance_plans,id',
            'farm_id' => 'required|exists:farms,id',
            'coverage_amount' => 'required|numeric|min:0',
            'premium_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $insurance->update([
            'insurer_id' => $request->insurer_id,
            'insurance_plan_id' => $request->insurance_plan_id,
            'farm_id' => $request->farm_id,
            'coverage_amount' => $request->coverage_amount,
            'premium_amount' => $request->premium_amount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('farmer.insurance.show', $insurance)
            ->with('success', 'Insurance policy updated successfully!');
    }

    public function destroy(Insurance $insurance)
    {
        // Ensure the insurance belongs to the authenticated user
        if ($insurance->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow deletion if status is pending
        if ($insurance->status !== 'pending') {
            return redirect()->route('farmer.insurance.show', $insurance)
                ->with('error', 'Cannot delete active or expired insurance policies.');
        }

        $insurance->delete();

        return redirect()->route('farmer.insurance.index')
            ->with('success', 'Insurance policy cancelled successfully!');
    }

    public function submitClaim(Request $request, Insurance $insurance)
    {
        // Ensure the insurance belongs to the authenticated user
        if ($insurance->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow claims on active policies
        if ($insurance->status !== 'active') {
            return back()->withErrors(['error' => 'Claims can only be submitted on active insurance policies.']);
        }

        $request->validate([
            'claim_amount' => 'required|numeric|min:0|max:' . $insurance->coverage_amount,
            'incident_date' => 'required|date|before_or_equal:today',
            'description' => 'required|string|max:1000',
            'evidence_files' => 'nullable|array',
            'evidence_files.*' => 'string'
        ]);

        $claim = $insurance->claims()->create([
            'user_id' => Auth::id(),
            'farm_id' => $insurance->farm_id,
            'claim_amount' => $request->claim_amount,
            'incident_date' => $request->incident_date,
            'description' => $request->description,
            'evidence_files' => $request->evidence_files ?? [],
            'status' => 'pending'
        ]);

        // Update insurance claims count
        $insurance->increment('claims_count');
        $insurance->update(['last_claim_date' => now()]);

        return redirect()->route('farmer.insurance.show', $insurance)
            ->with('success', 'Claim submitted successfully! It is now under review.');
    }

    public function cancelPolicy(Insurance $insurance)
    {
        // Ensure the insurance belongs to the authenticated user
        if ($insurance->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow cancellation of active policies
        if ($insurance->status !== 'active') {
            return back()->withErrors(['error' => 'Only active policies can be cancelled.']);
        }

        $insurance->update([
            'status' => 'cancelled',
            'end_date' => now()
        ]);

        return redirect()->route('farmer.insurance.show', $insurance)
            ->with('success', 'Insurance policy cancelled successfully!');
    }
}
