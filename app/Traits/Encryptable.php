<?php

namespace App\Traits;

use App\Services\AesEncryptionService;

trait Encryptable
{
    /**
     * Decrypt attribute value (PostgreSQL bytea safe)
     */
    protected function decryptAttribute($value)
    {
        if (!$value) {
            return null;
        }

        try {

            // PostgreSQL bytea may return resource stream
            if (is_resource($value)) {
                rewind($value);
                $value = stream_get_contents($value);
            }

            if (!is_string($value)) {
                return null;
            }

            return app(AesEncryptionService::class)->decrypt($value);

        } catch (\Throwable $e) {
            return null; // Prevent dashboard crash
        }
    }

    /**
     * Encrypt attribute value
     */
    protected function encryptAttribute($value)
    {
        if (!$value) {
            return null;
        }

        try {
            return app(AesEncryptionService::class)->encrypt($value);
        } catch (\Throwable $e) {
            return null;
        }
    }
}