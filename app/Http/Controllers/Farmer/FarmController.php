<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmController extends Controller
{
    public function index()
    {
        $farms = Farm::where('user_id', Auth::id())->get();

        return inertia('Farmer/FarmsPage', [
            'farms' => $farms
        ]);
    }

    public function create()
    {
        return inertia('Farmer/FarmCreatePage');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'contact' => 'required|string|max:255',
            'size' => 'required|numeric|min:0',
            'type' => 'required|string|in:crops,livestock,mixed',
        ]);

        Farm::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'address' => $request->address,
            'contact' => $request->contact,
            'size' => $request->size,
            'type' => $request->type,
        ]);

        return redirect()->route('farmer.farms.index')->with('success', 'Farm created successfully!');
    }

    public function show(Farm $farm)
    {
        // Ensure the farm belongs to the authenticated user
        if ($farm->user_id !== Auth::id()) {
            abort(403);
        }

        return inertia('Farmer/FarmShowPage', [
            'farm' => $farm->load(['transactions', 'plans', 'creditScore', 'insurances'])
        ]);
    }

    public function edit(Farm $farm)
    {
        // Ensure the farm belongs to the authenticated user
        if ($farm->user_id !== Auth::id()) {
            abort(403);
        }

        return inertia('Farmer/FarmEditPage', [
            'farm' => $farm
        ]);
    }

    public function update(Request $request, Farm $farm)
    {
        // Ensure the farm belongs to the authenticated user
        if ($farm->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'contact' => 'required|string|max:255',
            'size' => 'required|numeric|min:0',
            'type' => 'required|string|in:crops,livestock,mixed',
        ]);

        $farm->update([
            'name' => $request->name,
            'address' => $request->address,
            'contact' => $request->contact,
            'size' => $request->size,
            'type' => $request->type,
        ]);

        return redirect()->route('farmer.farms.index')->with('success', 'Farm updated successfully!');
    }

    public function destroy(Farm $farm)
    {
        // Ensure the farm belongs to the authenticated user
        if ($farm->user_id !== Auth::id()) {
            abort(403);
        }

        $farm->delete();

        return redirect()->route('farmer.farms.index')->with('success', 'Farm deleted successfully!');
    }
}
