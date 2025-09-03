<?php

namespace App\Http\Controllers\Lender;

use App\Http\Controllers\Controller;
use App\Models\Lender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class LenderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $lender = Lender::where('user_id', $user->id)->first();

        return inertia('Lender/DashboardPage', [
            'lender' => $lender
        ]);
    }

    public function profile()
    {
        $user = Auth::user();
        $lender = Lender::where('user_id', $user->id)->first();

        return inertia('Lender/ProfilePage', [
            'lender' => $lender,
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $lender = Lender::where('user_id', $user->id)->first();

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'contact' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
            'license_number' => 'nullable|string|max:100',
            'regulatory_body' => 'nullable|string|max:255',
        ]);

        // Update user information
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Update lender information
        $lender->update([
            'name' => $request->name,
            'address' => $request->address,
            'contact' => $request->contact,
            'website' => $request->website,
            'description' => $request->description,
            'license_number' => $request->license_number,
            'regulatory_body' => $request->regulatory_body,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        $lender = Lender::where('user_id', $user->id)->first();

        $request->validate([
            'notification_preferences' => 'array',
            'notification_preferences.email_notifications' => 'boolean',
            'notification_preferences.sms_notifications' => 'boolean',
            'notification_preferences.loan_updates' => 'boolean',
            'notification_preferences.payment_reminders' => 'boolean',
            'notification_preferences.report_notifications' => 'boolean',
            'business_hours' => 'nullable|string|max:255',
            'timezone' => 'nullable|string|max:50',
        ]);

        // Update notification preferences
        $settings = $lender->settings ?? [];
        $settings['notification_preferences'] = $request->notification_preferences;
        $settings['business_hours'] = $request->business_hours;
        $settings['timezone'] = $request->timezone;

        $lender->update([
            'settings' => $settings
        ]);

        return back()->with('success', 'Settings updated successfully.');
    }

    public function documents()
    {
        $user = Auth::user();
        $lender = Lender::where('user_id', $user->id)->first();

        // This would typically fetch documents from a documents table
        // For now, returning empty array
        $documents = [];

        return inertia('Lender/DocumentsPage', [
            'lender' => $lender,
            'documents' => $documents
        ]);
    }

    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string|max:100',
            'document_file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'description' => 'nullable|string|max:500',
        ]);

        // Implementation for document upload would go here
        // This would typically store the file and create a document record

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function deleteDocument($documentId)
    {
        // Implementation for document deletion would go here
        // This would typically delete the file and document record

        return back()->with('success', 'Document deleted successfully.');
    }
}
