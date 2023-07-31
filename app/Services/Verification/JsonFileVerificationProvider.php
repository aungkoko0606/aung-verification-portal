<?php

declare(strict_types=1);

namespace App\Services\Verification;

use App\Services\Util\AccredifyHttpClient;
use App\Services\Util\SignatureHash;

class JsonFileVerificationProvider implements FileVerificationProvider
{

    private const VERIFIED = 'verified';
    private const INVALID_RECIPIENT = 'invalid_recipient';
    private const INVALID_ISSUER = 'invalid_issuer';
    private const INVALID_SIGNATURE = 'invalid_signature';
    private const GOOGLE_DNS_API = 'https://dns.google/resolve?';

    private AccredifyHttpClient $httpClient;
    private SignatureHash $signatureHash;

    public function __construct(AccredifyHttpClient $httpClient, SignatureHash $signatureHash)
    {
        $this->httpClient = $httpClient;
        $this->signatureHash = $signatureHash;
    }

    public function isValidDataFile(array $fileContent): string
    {
        $recipientValidationStatus = strtolower($this->isValidRecipient($fileContent));
        if ($recipientValidationStatus !== self::VERIFIED) {
            return $recipientValidationStatus;
        }

        $issuerValidationStatus = strtolower($this->isValidIssuer($fileContent));
        if ($issuerValidationStatus !== self::VERIFIED) {
            return $issuerValidationStatus;
        }

        return strtolower($this->isValidSignature($fileContent));
    }

    public function isValidRecipient(array $fileContent): string
    {
        return isset($fileContent['data']['recipient']['name']) && isset($fileContent['data']['recipient']['email']) ? self::VERIFIED : self::INVALID_RECIPIENT;
    }

    public function isValidIssuer(array $fileContent): string
    {
        $issuer = $fileContent['data']['issuer'];

        if (isset($issuer['name']) && isset($issuer['identityProof'])) {
            if (isset($issuer['identityProof']['key']) && isset($issuer['identityProof']['location'])) {
                $data = ['name' => $issuer['identityProof']['location'], 'type' => 'TXT'];
                $response = $this->httpClient->get(self::GOOGLE_DNS_API, $data);
                $responseContent = json_decode($response['httpResponseContent'], true);

                if ($this->assertDNSRecord($responseContent, $issuer['identityProof']['key'])) {
                    return self::VERIFIED;
                }
            }
        }

        return self::INVALID_ISSUER;
    }

    public function isValidSignature(array $fileContent): string
    {
        $computedHash = $this->convertDataToHash($this->convertArrayToDotNotationKey($fileContent['data']));

        return $computedHash === $fileContent['signature']['targetHash'] ? self::VERIFIED : self::INVALID_SIGNATURE;
    }

    private function assertDNSRecord(array $responseContent, string $key): bool
    {
        if ($responseContent['Status'] === 0 && isset($responseContent['Answer'])) {
            foreach ($responseContent['Answer'] as $dnsRecord) {
                if (str_contains($dnsRecord['data'], $key)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function convertArrayToDotNotationKey(array $dataArray, string $prefix = ''): array
    {
        $result = [];
        foreach ($dataArray as $key => $value) {
            $newKey = $prefix ? $prefix . '.' . $key : $key;
            if (is_array($value)) {
                $result = array_merge($result, $this->convertArrayToDotNotationKey($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }
        return $result;
    }

    private function convertDataToHash(array $dotNotationArray): string
    {
        $dataHashArray = [];
        foreach ($dotNotationArray as $key => $value) {
            $dataHashArray[] = $this->signatureHash->getHashValue('sha256', json_encode([$key => $value]));
        }
        sort($dataHashArray);

        return $this->signatureHash->getHashValue('sha256', json_encode($dataHashArray));
    }
}
