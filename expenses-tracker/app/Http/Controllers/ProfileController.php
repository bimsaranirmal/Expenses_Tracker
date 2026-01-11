<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        // Handle notification settings
        $user->email_notifications = $request->has('email_notifications');
        $user->sms_notifications = $request->has('sms_notifications');
        if ($user->sms_notifications) {
            $user->mobile_number = $request->input('mobile_number');
        } else {
            $user->mobile_number = null;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Send a test SMS to the user.
     */
    public function testSms(Request $request)
    {
        $user = $request->user();
        $mobileNumber = $user->mobile_number;

        if (!$mobileNumber) {
            return response()->json(['message' => 'No mobile number found. Please save your mobile number first.'], 400);
        }

        $message = "Test SMS from Expense Tracker AI. Your SMS service is now working correctly!";
        $smsApiUrl = 'https://app.text.lk/api/http/sms/send';
        $smsParams = [
            'recipient' => $mobileNumber,
            'message'   => $message,
            'api_token' => '2363|CHS8YKmw9FQBvFTylIqt7I8mJoEDlBJ7lMnNi4Fqa2d3d61f',
            'sender_id' => 'TextLKDemo',
        ];

        try {
            $response = \Illuminate\Support\Facades\Http::post($smsApiUrl, $smsParams);
            
            \Log::info('Test SMS response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                return response()->json(['message' => 'Test SMS sent successfully! Check your phone.']);
            } else {
                $errorData = $response->json();
                $errorMessage = $errorData['message'] ?? 'Unknown API error';
                return response()->json(['message' => 'SMS Provider Error: ' . $errorMessage], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Test SMS failed: ' . $e->getMessage());
            return response()->json(['message' => 'System error while sending SMS.'], 500);
        }
    }
}
