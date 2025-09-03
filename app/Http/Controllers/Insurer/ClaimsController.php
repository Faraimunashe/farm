<?php

namespace App\Http\Controllers\Insurer;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Insurance;
use App\Models\Insurer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClaimsController extends Controller
{
    public function index()
    {
        $insurer = Insurer::where('user_id', Auth::id())->first();

        if (!$insurer) {
            return inertia('Insurer/ClaimsPage', [
                'claims' => collect([])->paginate(15)
            ]);
        }

        $claims = Claim::whereHas('insurance.insurancePlan', function($query) use ($insurer) {
            $query->where('insurer_id', $insurer->id);
        })
        ->with(['user', 'farm', 'insurance.insurancePlan'])
        ->latest()
        ->paginate(15);

        return inertia('Insurer/ClaimsPage', [
            'claims' => $claims
        ]);
    }

    public function show(Claim $claim)
    {
        $insurer = Insurer::where('user_id', Auth::id())->first();

        if (!$insurer) {
            abort(403);
        }

        $claim->load(['user', 'farm', 'insurance.insurancePlan.insurer']);

        if (!$claim->insurance->insurancePlan || $claim->insurance->insurancePlan->insurer_id !== $insurer->id) {
            abort(403);
        }

        return inertia('Insurer/ClaimShowPage', [
            'claim' => $claim
        ]);
    }

    public function updateStatus(Request $request, Claim $claim)
    {
        $insurer = Insurer::where('user_id', Auth::id())->first();

        if (!$insurer) {
            abort(403);
        }

        $claim->load('insurance.insurancePlan');

        if (!$claim->insurance->insurancePlan || $claim->insurance->insurancePlan->insurer_id !== $insurer->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,investigating,approved,rejected',
            'approved_amount' => 'nullable|numeric|min:0|max:' . $claim->claim_amount,
            'notes' => 'nullable|string|max:1000',
            'rejection_reason' => 'nullable|string|max:1000|required_if:status,rejected',
        ]);

        $updateData = [
            'status' => $request->status,
            'notes' => $request->notes,
        ];

        if ($request->status === 'approved') {
            $updateData['approved_amount'] = $request->approved_amount;
            $updateData['approved_by'] = Auth::id();
            $updateData['approved_at'] = now();
        } elseif ($request->status === 'rejected') {
            $updateData['rejection_reason'] = $request->rejection_reason;
        }

        $claim->update($updateData);

        return redirect()->route('insurer.claims.show', $claim)->with('success', 'Claim status updated successfully!');
    }

    public function addInvestigationNotes(Request $request, Claim $claim)
    {
        $insurer = Insurer::where('user_id', Auth::id())->first();

        if (!$insurer) {
            abort(403);
        }

        $claim->load('insurance.insurancePlan');

        if (!$claim->insurance->insurancePlan || $claim->insurance->insurancePlan->insurer_id !== $insurer->id) {
            abort(403);
        }

        $request->validate([
            'investigation_notes' => 'required|string|max:2000',
        ]);

        $claim->update([
            'investigation_notes' => $request->investigation_notes,
            'status' => 'investigating',
        ]);

        return redirect()->route('insurer.claims.show', $claim)->with('success', 'Investigation notes added successfully!');
    }
}
