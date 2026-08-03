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
        
        try {
            $authKey = env('MSGCLUB_AUTH_KEY', 'f0ceeb95f5a928c3814bc3b3ee962b');
            $senderId = env('MSGCLUB_SENDER_ID', 'TBTSGN');
            $routeId = env('MSGCLUB_ROUTE_ID', '8');

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('http://msg.msgclub.net/rest/services/sendSMS/sendGroupSms?AUTH_KEY=' . $authKey, [
                'smsContent' => "Your TourRaja verification OTP is {$otp}. Valid for 5 minutes.",
                'groupId' => '0',
                'routeId' => $routeId,
                'mobileNumbers' => $cleanPhone,
                'senderId' => $senderId,
                'smsContentType' => 'ENGLISH',
                'concentFailoverId' => '30'
            ]);

            if ($response->failed()) {
                Log::error("MSGClub API HTTP error", ['response' => $response->body()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send OTP.'
                ], 500);
            }
            
            $resData = $response->json();
            // If the API returns a specific error format, log it
            Log::info("MSGClub API response", ['response' => $resData]);
            
        } catch (\Exception $e) {
            Log::error("MSGClub Exception", ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to SMS service.'
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
