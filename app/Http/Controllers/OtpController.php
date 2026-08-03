<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
        
        // Generate a random 6-digit OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // In a real application, you'd call your SMS provider API here:
        // Twilio::message($phone, "Your TourRaja verification code is: $otp");
        
        // For now, we log the OTP to laravel.log so it can be tested locally
        Log::info("OTP for {$phone} is {$otp}");

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
