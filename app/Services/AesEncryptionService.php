<?php

namespace App\Services;

class AesEncryptionService
{
    private string $key;

    public function __construct()
    {
        $this->key = config('app.shared_aes_key');

        if (strlen($this->key) !== 32) {
            throw new \Exception('Encryption key must be exactly 32 bytes.');
        }
    }

    public function encrypt(?string $plainText): ?string
    {
        if ($plainText === null) {
            return null;
        }

        $iv = random_bytes(16);

        $cipherText = openssl_encrypt(
            $plainText,
            'AES-256-CBC',
            $this->key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return $iv . $cipherText;
    }


    public function decrypt(mixed $encryptedData): ?string
    {
        if (!$encryptedData) {
            return null;
        }

        try {

            // Handle PostgreSQL bytea stream resource
            if (is_resource($encryptedData)) {
                $encryptedData = stream_get_contents($encryptedData);
            }

            if (!is_string($encryptedData)) {
                return null;
            }

            if (strlen($encryptedData) < 16) {
                return null;
            }

            $iv = substr($encryptedData, 0, 16);
            $cipherText = substr($encryptedData, 16);

            $plainText = openssl_decrypt(
                $cipherText,
                'AES-256-CBC',
                $this->key,
                OPENSSL_RAW_DATA,
                $iv
            );

            return $plainText !== false ? $plainText : null;

        } catch (\Throwable) {
            return null;
        }
    }
}