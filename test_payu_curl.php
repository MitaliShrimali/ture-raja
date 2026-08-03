<?php
$payuKey="gtKFFx"; 
$txnid="TEST_" . time(); 
$payuAmount="10.00"; 
$productinfo="test"; 
$firstname="test"; 
$email="test@test.com"; 
$udf2="item"; 
$payuSalt="eCwWELxi";  

$hashString = $payuKey . "|" . $txnid . "|" . $payuAmount . "|" . $productinfo . "|" . $firstname . "|" . $email . "||" . $udf2 . "|||||||||" . $payuSalt; 
$hash = strtolower(hash("sha512", $hashString));

$postData = [
    "key" => $payuKey,
    "txnid" => $txnid,
    "amount" => $payuAmount,
    "productinfo" => $productinfo,
    "firstname" => $firstname,
    "email" => $email,
    "phone" => "9999999999",
    "surl" => "http://localhost/success",
    "furl" => "http://localhost/fail",
    "hash" => $hash,
    "service_provider" => "payu_paisa",
    "udf1" => "",
    "udf2" => $udf2,
    "udf3" => "",
    "udf4" => "",
    "udf5" => "",
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://test.payu.in/_payment");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$server_output = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Status: " . $httpcode . "\n";
if (strpos($server_output, 'incorrectly calculated hash parameter') !== false) {
    echo "Result: INVALID HASH\n";
} else if (strpos($server_output, 'invalid merchant') !== false || strpos($server_output, 'Merchant Key') !== false) {
    echo "Result: INVALID MERCHANT\n";
} else {
    echo "Result: SUCCESS or OTHER ERROR\n";
    echo substr(strip_tags($server_output), 0, 500);
}
?>
