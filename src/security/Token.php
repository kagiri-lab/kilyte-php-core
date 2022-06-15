<?php

namespace kilyte\security;


class Token
{

    public function generathex(int $length = 32)
    {
        $token = openssl_random_pseudo_bytes($length);
        $token = bin2hex($token);
        $stringenc = new StringEncrypt;
        $token = $stringenc->openssl_encrypt($token);
        return $token;
    }

}
