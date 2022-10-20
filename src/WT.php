<?php

declare(strict_types=1);

namespace WT;

class WT
{
    /** @var string For a list of available cipher methods, use {@see openssl_get_cipher_methods()}. */
    private $cypherMethod;
    /**
     * @var string Initialization vector. A fixed-size input to a cryptographic primitive that is typically required to
     *      be random or pseudo-random. Must be 8 characters long.
     */
    private $iv;
    /** @var string Key that will be used to encrypt/decrypt the token */
    private $key;

    public function __construct(string $cypherMethod, string $iv, ?string $key = null)
    {
        $this->cypherMethod = $cypherMethod;
        $this->iv           = $iv;
        if ($key === null) {
            return;
        }
        $this->key = $key;
    }

    /**
     * @param mixed $object Value that will be encrypted. Can be any type except a resource.
     */
    public function encode($object, ?string $key = null) : string
    {
        if ($this->key === null && $key === null) {
            throw new \InvalidArgumentException('Encryption key must be initialized or passed as argument');
        }

        $json = json_encode($object);
        if ($json === false) {
            throw new \RuntimeException('Failed to json encode param ' . print_r($object, true));
        }

        $key = $key ?? $this->key;
        $key = hash('sha256', $key);

        $ivPrefix = substr(base64_encode(hash('sha256', $json . time())), 0, 8);
        $iv       = $ivPrefix . $this->iv;

        $output = openssl_encrypt($json, $this->cypherMethod, $key, 0, $iv);
        if ($output === false) {
            throw new \RuntimeException('Failed to encrypt value');
        }

        $output = base64_encode($output);

        return $ivPrefix . $output;
    }

    /**
     * @return mixed the value encoded
     */
    public function decode(string $token, ?string $key = null)
    {
        if ($this->key === null && $key === null) {
            throw new \InvalidArgumentException('Encryption key must be initialized or passed as argument');
        }

        $ivPrefix = substr($token, 0, 8);
        $token    = substr($token, 8);

        $key = $key ?? $this->key;
        $key = hash('sha256', $key);
        $iv  = $ivPrefix . $this->iv;

        $base64Decode = base64_decode($token);
        if ($base64Decode === false) {
            throw new \RuntimeException('Failed to decode data');
        }

        $opensslDecrypt = openssl_decrypt($base64Decode, $this->cypherMethod, $key, 0, $iv);
        if ($opensslDecrypt === false) {
            return null;
        }

        return json_decode($opensslDecrypt);
    }
}
