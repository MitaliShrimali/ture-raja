<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MsgClubService
{
    public function sendOtpSms(string $mobileNumber, string $otp)
    {
        try {
            $authKey = config('services.msgclub.auth_key') ?: env('MSGCLUB_AUTH_KEY');
            $apiUrl = config('services.msgclub.api_url') ?: env('MSGCLUB_API_URL', 'http://msg.msgclub.net/rest/services/sendSMS/sendGroupSms');
            
            $senderId = config('services.msgclub.sender_id') ?: env('MSGCLUB_SENDER_ID', 'TOURRJ');
            $routeId = config('services.msgclub.route_id') ?: env('MSGCLUB_ROUTE_ID', '8');
            $entityId = config('services.msgclub.entity_id') ?: env('MSGCLUB_ENTITY_ID', '1701173441471358135');
            $tmId = config('services.msgclub.tm_id') ?: env('MSGCLUB_TM_ID', '1002408235216785541');
            $templateId = config('services.msgclub.template_id') ?: env('MSGCLUB_TEMPLATE_ID', '1707173467195182193');
            $smsContentType = config('services.msgclub.sms_content_type') ?: env('MSGCLUB_SMS_CONTENT_TYPE', 'english');

            $message = "TourRaja verification code {$otp} Please do not share your OTP or Password with anyone to avoid misuse of your account";

            // Clean mobile number
            $cleanMobile = preg_replace('/[^0-9]/', '', $mobileNumber);
            
            // Normalize Indian numbers to 10 digits if they include the 91 country code
            if (strlen($cleanMobile) == 12 && substr($cleanMobile, 0, 2) == '91') {
                $cleanMobile = substr($cleanMobile, 2);
            }
            
            $response = Http::get($apiUrl, [
                'AUTH_KEY' => $authKey,
                'message' => $message,
                'senderId' => $senderId,
                'routeId' => $routeId,
                'mobileNos' => $cleanMobile,
                'smsContentType' => $smsContentType,
                'entityid' => $entityId,
                'tmid' => $tmId,
                'templateid' => $templateId,
            ]);

            $responseData = $response->json();
            
            // Check for successful response (responseCode: "3001")
            if (isset($responseData['responseCode']) && $responseData['responseCode'] == '3001') {
                Log::info("MSGClub SMS sent successfully to {$cleanMobile}.");
                return true;
            } else {
                Log::error("MSGClub SMS failed.", [
                    'response' => $responseData,
                    'phone' => $cleanMobile
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error("MSGClub SMS Exception", ['error' => $e->getMessage(), 'phone' => $mobileNumber]);
            return false;
        }
    }

    public function sendCustomSms(string $mobileNumber, string $messageText, ?string $templateId = null)
    {
        try {
            $authKey = config('services.msgclub.auth_key') ?: env('MSGCLUB_AUTH_KEY');
            $apiUrl = config('services.msgclub.api_url') ?: env('MSGCLUB_API_URL', 'http://msg.msgclub.net/rest/services/sendSMS/sendGroupSms');
            
            $senderId = config('services.msgclub.sender_id') ?: env('MSGCLUB_SENDER_ID', 'TOURRJ');
            $routeId = config('services.msgclub.route_id') ?: env('MSGCLUB_ROUTE_ID', '8');
            $entityId = config('services.msgclub.entity_id') ?: env('MSGCLUB_ENTITY_ID', '1701173441471358135');
            $tmId = config('services.msgclub.tm_id') ?: env('MSGCLUB_TM_ID', '1002408235216785541');
            $targetTemplateId = $templateId ?: (config('services.msgclub.template_id') ?: env('MSGCLUB_TEMPLATE_ID', '1707173467195182193'));
            $smsContentType = config('services.msgclub.sms_content_type') ?: env('MSGCLUB_SMS_CONTENT_TYPE', 'english');

            $cleanMobile = preg_replace('/[^0-9]/', '', $mobileNumber);
            if (strlen($cleanMobile) == 12 && substr($cleanMobile, 0, 2) == '91') {
                $cleanMobile = substr($cleanMobile, 2);
            }
            
            $response = Http::get($apiUrl, [
                'AUTH_KEY' => $authKey,
                'message' => $messageText,
                'senderId' => $senderId,
                'routeId' => $routeId,
                'mobileNos' => $cleanMobile,
                'smsContentType' => $smsContentType,
                'entityid' => $entityId,
                'tmid' => $tmId,
                'templateid' => $targetTemplateId,
            ]);

            $responseData = $response->json();
            if (isset($responseData['responseCode']) && $responseData['responseCode'] == '3001') {
                Log::info("MSGClub Custom SMS sent to {$cleanMobile}.");
                return true;
            } else {
                Log::error("MSGClub Custom SMS failed.", ['response' => $responseData, 'phone' => $cleanMobile]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error("MSGClub Custom SMS Exception", ['error' => $e->getMessage(), 'phone' => $mobileNumber]);
            return false;
        }
    }
}
