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

    public function decrypt(?string $encryptedData): ?string
    {
        if ($encryptedData === null) {
            return null;
        }

        if (is_resource($encryptedData)) {
            $encryptedData = stream_get_contents($encryptedData);
        }

        $iv = substr($encryptedData, 0, 16);
        $cipherText = substr($encryptedData, 16);

        return openssl_decrypt(
            $cipherText,
            'AES-256-CBC',
            $this->key,
            OPENSSL_RAW_DATA,
            $iv
        );
    }
}