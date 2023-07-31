<?php
declare(strict_types=1);

namespace App\Services\Util;

class SignatureHash
{

    public function getHashValue(string $hashAlgorithm, string $data) : string
    {
        return hash($hashAlgorithm, $data);
    }

}
