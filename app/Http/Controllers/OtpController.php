<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;

class OtpController extends Controller
{
    /**
     * Send OTP to the given phone number.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $phone = $request->phone;
        
        // Clean phone number for MSGClub API (digits only)
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        
        // Generate a random 6-digit OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $email = $request->email;
        
        try {
            // TEMPORARY: Send OTP via email instead of SMS
            \Illuminate\Support\Facades\Mail::raw("Your Tour Raja verification OTP for phone {$phone} is {$otp}. Valid for 5 minutes.", function ($message) use ($email) {
                // If email is missing for some reason, fallback to a default so it doesn't crash
                $targetEmail = $email ?: 'tour raja@emperorsmartsolutions.com';
                $message->to($targetEmail)
                        ->subject('Tour Raja OTP Verification');
            });
            
            Log::info("Sent OTP via Email to {$email}", ['otp' => $otp, 'phone' => $phone]);

        } catch (\Throwable $e) {
            Log::error("Email OTP Exception", ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to Mail service.'
            ], 500);
        }

        // Store OTP in cache for 5 minutes
        Cache::put('otp_' . $phone, $otp, now()->addMinutes(5));

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully!'
        ]);
    }

    /**
     * Verify the entered OTP against the cached one.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|string',
        ]);

        $phone = $request->phone;
        $enteredOtp = $request->otp;

        $cachedOtp = Cache::get('otp_' . $phone);

        if (!$cachedOtp) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired or was not requested.'
            ], 400);
        }

        if ($cachedOtp == $enteredOtp) {
            // OTP matched, remove it from cache
            Cache::forget('otp_' . $phone);
            
            // Optionally, mark this session as phone verified
            session()->put('verified_phone', $phone);

            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Incorrect OTP. Please try again.'
        ], 400);
    }
}
