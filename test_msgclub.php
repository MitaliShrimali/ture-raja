<?php

$apiUrl = "http://msg.msgclub.net/rest/services/sendSMS/sendGroupSms";
$authKey = "f0ceeb95f5a928c3814bc3b3ee962b";
$message = "TourRaja verification code 123456 Please do not share your OTP or Password with anyone to avoid misuse of your account";
$senderId = "TBTSGN";
$routeId = "8";
$mobileNos = "9173282319";
$smsContentType = "english";
$entityId = "1701173441471358135";
$tmId = "1002408235216785541";
$templateId = "1707173467195182193";

$url = $apiUrl . '?' . http_build_query([
    'AUTH_KEY' => $authKey,
    'message' => $message,
    'senderId' => $senderId,
    'routeId' => $routeId,
    'mobileNos' => $mobileNos,
    'smsContentType' => $smsContentType,
    'entityid' => $entityId,
    'tmid' => $tmId,
    'templateid' => $templateId,
]);

echo "Requesting URL: $url\n";
$response = file_get_contents($url);
echo "Response: $response\n";

