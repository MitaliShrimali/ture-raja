<?php

namespace App\Services;

class PayUService
{
    protected $merchantKey;
    protected $merchantSalt;
    protected $baseUrl;
    protected $testMode;

    public function __construct()
    {
        $this->merchantKey = trim(config('services.payu.merchant_key'));
        $this->merchantSalt = trim(config('services.payu.merchant_salt'));
        $this->testMode = config('services.payu.test_mode', false);
        $this->baseUrl = $this->testMode ? 'https://test.payu.in/_payment' : 'https://secure.payu.in/_payment';
    }

    /**
     * Generate the SHA-512 hash for initiating a payment.
     * The exact formula is:
     * sha512(key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10|SALT)
     */
    public function generatePaymentHash(array $posted): array
    {
        $hashVarsSeq = explode('|', 'key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10');
        $hashString = '';
        
        foreach ($hashVarsSeq as $hash_var) {
            $hashString .= (isset($posted[$hash_var]) ? $posted[$hash_var] : '') . '|';
        }
        $hashString .= $this->merchantSalt;
        
        $hash = strtolower(hash('sha512', $hashString));
        
        if (config('app.debug')) {
            \Log::info('PayU Hash Generation', [
                'hash_string' => str_replace($this->merchantSalt, '***SALT***', $hashString),
                'generated_hash' => $hash,
                'txnid' => $posted['txnid'] ?? ''
            ]);
        }
        
        return [
            'hash' => $hash,
            'hashString' => str_replace($this->merchantSalt, '***SALT***', $hashString)
        ];
    }

    /**
     * Verify the reverse hash returned by PayU after a payment.
     * The exact formula is:
     * sha512(SALT|status|udf10|udf9|udf8|udf7|udf6|udf5|udf4|udf3|udf2|udf1|email|firstname|productinfo|amount|txnid|key)
     * Note: If additionalCharges is present, it is prepended.
     */
    public function verifyResponseHash(array $posted): bool
    {
        $status = $posted['status'] ?? '';
        $postedHash = $posted['hash'] ?? '';
        $additionalCharges = $posted['additionalCharges'] ?? null;
        
        $hashVarsSeq = explode('|', 'key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10');
        $hashVarsSeqRev = array_reverse($hashVarsSeq);
        
        $retHashSeq = '';
        if (!empty($additionalCharges)) {
            $retHashSeq .= $additionalCharges . '|';
        }
        $retHashSeq .= $this->merchantSalt . '|' . $status . '|';
        
        foreach ($hashVarsSeqRev as $hash_var) {
            $retHashSeq .= (isset($posted[$hash_var]) ? $posted[$hash_var] : '') . '|';
        }
        $retHashSeq = rtrim($retHashSeq, '|');
        
        $calculatedHash = strtolower(hash('sha512', $retHashSeq));
        
        if (config('app.debug')) {
            \Log::info('PayU Reverse Hash Verification', [
                'ret_hash_string' => str_replace($this->merchantSalt, '***SALT***', $retHashSeq),
                'calculated_hash' => $calculatedHash,
                'posted_hash' => $postedHash,
                'match' => ($calculatedHash === $postedHash)
            ]);
        }
        
        return $calculatedHash === $postedHash;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
    
    public function getMerchantKey(): string
    {
        return $this->merchantKey;
    }
}
