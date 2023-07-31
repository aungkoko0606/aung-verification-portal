<?php

namespace App\Services\Verification;

interface FileVerificationProvider
{

    public function isValidDataFile(array $fileContent) : string;

    public function isValidRecipient(array $fileContent) : string;

    public function isValidIssuer(array $fileContent) : string;

    public function isValidSignature(array $fileContent) : string;

}
