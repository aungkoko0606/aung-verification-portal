<?php

declare(strict_types=1);

namespace App\Services\Verification;

class FileVerificationProviderFactory
{

    private const JSON_FILE_TYPE = 'json';

    public function create(string $fileType = self::JSON_FILE_TYPE): FileVerificationProvider
    {
        switch (strtolower($fileType)) {
            case self::JSON_FILE_TYPE:
                return app(JsonFileVerificationProvider::class);

            default:
                throw new \Exception('Only Json File Provider is available');
        }
    }

}
