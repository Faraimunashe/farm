<?php

namespace App\Http\Controllers\Insurer;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Insurance;
use App\Models\Insurer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentsController extends Controller
{
    public function index()
    {
        $insurer = Insurer::where('user_id', Auth::id())->first();

        if (!$insurer) {
            return inertia('Insurer/PaymentsPage', [
                'payments' => collect([])->paginate(15)
            ]);
        }

        $payments = Payment::whereHas('insurance.insurancePlan', function($query) use ($insurer) {
            $query->where('insurer_id', $insurer->id);
        })
        ->with(['user', 'insurance.insurancePlan'])
        ->latest()
        ->paginate(15);

        // Calculate payment statistics
        $totalRevenue = $payments->where('status', 'completed')->sum('amount');
        $pendingPayments = $payments->where('status', 'pending')->sum('amount');
        $failedPayments = $payments->where('status', 'failed')->sum('amount');

        return inertia('Insurer/PaymentsPage', [
            'payments' => $payments,
            'stats' => [
                'totalRevenue' => $totalRevenue,
                'pendingPayments' => $pendingPayments,
                'failedPayments' => $failedPayments,
            ]
        ]);
    }

    public function show(Payment $payment)
    {
        $insurer = Insurer::where('user_id', Auth::id())->first();

        if (!$insurer) {
            abort(403);
        }

        $payment->load(['user', 'insurance.insurancePlan.insurer']);

        if (!$payment->insurance->insurancePlan || $payment->insurance->insurancePlan->insurer_id !== $insurer->id) {
            abort(403);
        }

        return inertia('Insurer/PaymentShowPage', [
            'payment' => $payment
        ]);
    }

    public function updateStatus(Request $request, Payment $payment)
    {
        $insurer = Insurer::where('user_id', Auth::id())->first();

        if (!$insurer) {
            abort(403);
        }

        $payment->load('insurance.insurancePlan');

        if (!$payment->insurance->insurancePlan || $payment->insurance->insurancePlan->insurer_id !== $insurer->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,completed,failed',
            'notes' => 'nullable|string|max:1000',
        ]);

        $updateData = [
            'status' => $request->status,
        ];

        if ($request->status === 'completed') {
            $updateData['payment_date'] = now();

            // Update insurance payment status
            $payment->insurance->update([
                'payment_status' => 'paid',
                'status' => 'active'
            ]);
        } elseif ($request->status === 'failed') {
            $payment->insurance->update([
                'payment_status' => 'failed'
            ]);
        }

        $payment->update($updateData);

        return redirect()->route('insurer.payments.show', $payment)->with('success', 'Payment status updated successfully!');
    }

    public function export(Request $request)
    {
        $insurer = Insurer::where('user_id', Auth::id())->first();

        if (!$insurer) {
            abort(403);
        }

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:pending,completed,failed',
            'payment_method' => 'nullable|string',
        ]);

        $query = Payment::whereHas('insurance.insurancePlan', function($query) use ($insurer) {
            $query->where('insurer_id', $insurer->id);
        })->with(['user', 'insurance.insurancePlan']);

        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->latest()->get();

        // Return data for export (could be CSV, Excel, etc.)
        return inertia('Insurer/PaymentsExportPage', [
            'payments' => $payments,
            'filters' => $request->only(['start_date', 'end_date', 'status', 'payment_method'])
        ]);
    }
}
