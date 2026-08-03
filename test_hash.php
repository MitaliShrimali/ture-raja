<?php
$payuKey="key"; 
$txnid="txnid"; 
$payuAmount="10.00"; 
$productinfo="info"; 
$firstname="name"; 
$email="email"; 
$udf2="item"; 
$payuSalt="salt"; 

// Old logic
$s1 = $payuKey . "|" . $txnid . "|" . $payuAmount . "|" . $productinfo . "|" . $firstname . "|" . $email . "||" . $udf2 . "|||||||||" . $payuSalt; 

// New logic
$hashVarsSeq = explode("|", "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10"); 
$posted = [
    "key"=>$payuKey,
    "txnid"=>$txnid,
    "amount"=>$payuAmount,
    "productinfo"=>$productinfo,
    "firstname"=>$firstname,
    "email"=>$email,
    "udf1"=>"",
    "udf2"=>$udf2,
    "udf3"=>"",
    "udf4"=>"",
    "udf5"=>"",
    "udf6"=>"",
    "udf7"=>"",
    "udf8"=>"",
    "udf9"=>"",
    "udf10"=>""
]; 
$s2 = ""; 
foreach($hashVarsSeq as $v) { 
    $s2 .= (isset($posted[$v]) ? $posted[$v] : "") . "|"; 
} 
$s2 .= $payuSalt; 

echo "S1: " . $s1 . "\n";
echo "S2: " . $s2 . "\n";
if ($s1 === $s2) {
    echo "MATCH!\n";
} else {
    echo "MISMATCH!\n";
}
?>
