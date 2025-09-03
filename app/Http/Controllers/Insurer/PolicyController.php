<?php

namespace App\Http\Controllers\Insurer;

use App\Http\Controllers\Controller;
use App\Models\Insurance;
use App\Models\Insurer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PolicyController extends Controller
{
    public function index()
    {
        $insurer = Insurer::where('user_id', Auth::id())->first();

        if (!$insurer) {
            return inertia('Insurer/PoliciesPage', [
                'policies' => collect([])->paginate(15)
            ]);
        }

        $policies = Insurance::whereHas('insurancePlan', function($query) use ($insurer) {
            $query->where('insurer_id', $insurer->id);
        })
        ->with(['user', 'farm', 'insurancePlan.insurer'])
        ->latest()
        ->paginate(15);

        return inertia('Insurer/PoliciesPage', [
            'policies' => $policies
        ]);
    }

    public function show(Insurance $policy)
    {
        $insurer = Insurer::where('user_id', Auth::id())->first();

        if (!$insurer) {
            abort(403);
        }

        $policy->load(['user', 'farm', 'insurancePlan.insurer']);

        if (!$policy->insurancePlan || $policy->insurancePlan->insurer_id !== $insurer->id) {
            abort(403);
        }

        return inertia('Insurer/PolicyShowPage', [
            'policy' => $policy
        ]);
    }

    public function updateStatus(Request $request, Insurance $policy)
    {
        $insurer = Insurer::where('user_id', Auth::id())->first();

        if (!$insurer) {
            abort(403);
        }

        $policy->load('insurancePlan');

        if (!$policy->insurancePlan || $policy->insurancePlan->insurer_id !== $insurer->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:active,expired,cancelled',
        ]);

        $policy->update([
            'status' => $request->status,
        ]);

        return redirect()->route('insurer.policies.show', $policy)->with('success', 'Policy status updated successfully!');
    }
}
