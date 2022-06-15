<?php

namespace kilyte\security;


class StringEncrypt
{

    private $encyption_key = 'KiLyte';
    private $cipher_algo = "AES-128-CBC";
    private $hash_hmac_alog = 'sha256';

    public function __construct()
    {
        
    }

    public function openssl_encrypt($string)
    {
        if (empty($string))
            return false;
        $ivlen = openssl_cipher_iv_length($this->cipher_algo);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext = openssl_encrypt($string, $this->cipher_algo, $this->encyption_key, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac($this->hash_hmac_alog, $ciphertext, $this->encyption_key, true);
        $ciphertext = base64_encode($iv . $hmac . $ciphertext);
        return $ciphertext;
    }

    public function openssl_decrypt($ciphertext)
    {
        if (empty($ciphertext))
            return false;

        $c = base64_decode($ciphertext);
        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len = 32);
        $ciphertext = substr($c, $ivlen + $sha2len);
        $original_plaintext = openssl_decrypt($ciphertext, $cipher, $this->encyption_key, $options = OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac($this->hash_hmac_alog, $ciphertext, $this->encyption_key, true);
        if (hash_equals($hmac, $calcmac)) {
            return $original_plaintext;
        } else
            return false;
    }

}
