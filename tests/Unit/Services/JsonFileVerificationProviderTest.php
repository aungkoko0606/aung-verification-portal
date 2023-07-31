<?php
declare(strict_types=1);
namespace Tests\Unit\Services;

use App\Services\Util\AccredifyHttpClient;
use App\Services\Util\SignatureHash;
use App\Services\Verification\JsonFileVerificationProvider;
use PHPUnit\Framework\TestCase;

class JsonFileVerificationProviderTest extends TestCase
{
    private const GOOGLE_DNS_API = 'https://dns.google/resolve?';

    public function test_recipient_from_json_is_valid()
    {
        $httpClient = $this->createMock(AccredifyHttpClient::class);
        $httpClient->method('get')->with(self::GOOGLE_DNS_API, ['name' => 'ropstore.accredify.io', 'type' => 'TXT']
        )->willReturn(
            [
                'httpResponseStatus' => 200,
                'httpResponseContent' => "{\"Status\":0,\"TC\":false,\"RD\":true,\"RA\":true,\"AD\":false,\"CD\":false,\"Question\":[{\"name\":\"ropstore.accredify.io.\",\"type\":16}],\"Answer\":[{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x05b642ff12a4ae545357d82ba4f786f3aed84214#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x06a464971ea723177ef83df7b39dd63c373a6905#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x2FbBdba8BF963b1648e4755f587547Bd0Ea7685a#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x496a0f8348a092660c435cee0bb597b473ff8173#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x757cd434dd1e93d47a4c6ed7a1b31bd88d984b45#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x7c2f9fc979c13a3c86be64b8d2063f05ce799f6d#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x7f7b4ad63fbfd2b1bc5bd7ec269e22a53b28f6f3#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x8abde9e6aeeebfff9f2e24014582881a007ce74f#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x92557d2d818fea37ee8808219e77a93aef0f5e17#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xa979aeb39dd2307e060d7d11e1a446f358f0d21c#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xad4dbc3ad9dc3b7f52609d5b23f3c22e3e7cefa1#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xc04370e761f72e7d2985e274f914221efe51886e#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xed368d1c74cdc731e119c4ca4acdf65add9af735#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0x0B209E53234e5E9744d70509b74d66358df0bb27\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0x8170f595b2b151e0e06052b79e81b80117f71181\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xa57a86ff03f536ccfce12ebfcd3361af421b82ed\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xad90a8b96fa17ae22566beb2eb5f3730771ba9ae\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xd604c626018d3924bfaa3b21e168451850b0fb14\"}],\"Comment\":\"Response from 205.251.199.2.\"}"
            ]
        );

        $hashValues = array
        (
            '8d79f393cc294fd3daca0402209997db5ff8a2ad1a498702f0956952677881ae',
            'cd77eab0fa4b92136f883dfe6fe63d7ee68a98a7697874609a5f9d24adaa0f04',
            'd94a0e7c2e7f61c7b29fede334c1b501a8b7cc8d46876273e92c4412ad82f575',
            'b38da593123c5295845996b08502a115c2ed5e1f42745ed45fba2a0b4ea3ed47',
            '88e287c3b0e2fcaeac173b7a20e3357342ad75cb2ceb849b3f7176c4026379b2',
            '9cba7ec835e861763731506e3b7712cfcab46ccb735fadd9a4e7c85716972144',
            '39fa9881a7607ee77cfaa82b982f4e809fc96c8ebf4891d98349ba3d71bc1a8e',
            '14ee1b33dd3084a127a6e2e6807fca79f317e2df3a9069c50e5f5adb4da84bb8',
            'a8aa49c6d150fab1fd77213f1f182c42ece261b30822b0c1c12826ef4599238b'
        );

        $signatureHash = $this->createMock(SignatureHash::class);
        $signatureHash->method('getHashValue')->with('sha256', $hashValues)->willReturn(
            "288f94aadadf486cfdad84b9f4305f7d51eac62db18376d48180cc1dd2047a0e"
        );

        $jsonFileVerificationProvider = new JsonFileVerificationProvider(
            new AccredifyHttpClient(), new SignatureHash()
        );
        $recipientResult = $jsonFileVerificationProvider->isValidRecipient($this->validData());

        $this->assertEquals('verified', $recipientResult);
    }

    public function test_recipient_from_json_is_invalid()
    {
        $jsonFileVerificationProvider = new JsonFileVerificationProvider(
            new AccredifyHttpClient(), new SignatureHash()
        );
        $recipientResult = $jsonFileVerificationProvider->isValidRecipient($this->invalidRecipientData());

        $this->assertEquals('invalid_recipient', $recipientResult);
    }

    public function test_issuer_from_json_is_valid()
    {
        $httpClient = $this->createMock(AccredifyHttpClient::class);
        $httpClient->method('get')->with(self::GOOGLE_DNS_API, ['name' => 'ropstore.accredify.io', 'type' => 'TXT']
        )->willReturn(
            [
                'httpResponseStatus' => 200,
                'httpResponseContent' => "{\"Status\":0,\"TC\":false,\"RD\":true,\"RA\":true,\"AD\":false,\"CD\":false,\"Question\":[{\"name\":\"ropstore.accredify.io.\",\"type\":16}],\"Answer\":[{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x05b642ff12a4ae545357d82ba4f786f3aed84214#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x06a464971ea723177ef83df7b39dd63c373a6905#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x2FbBdba8BF963b1648e4755f587547Bd0Ea7685a#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x496a0f8348a092660c435cee0bb597b473ff8173#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x757cd434dd1e93d47a4c6ed7a1b31bd88d984b45#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x7c2f9fc979c13a3c86be64b8d2063f05ce799f6d#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x7f7b4ad63fbfd2b1bc5bd7ec269e22a53b28f6f3#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x8abde9e6aeeebfff9f2e24014582881a007ce74f#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x92557d2d818fea37ee8808219e77a93aef0f5e17#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xa979aeb39dd2307e060d7d11e1a446f358f0d21c#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xad4dbc3ad9dc3b7f52609d5b23f3c22e3e7cefa1#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xc04370e761f72e7d2985e274f914221efe51886e#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xed368d1c74cdc731e119c4ca4acdf65add9af735#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0x0B209E53234e5E9744d70509b74d66358df0bb27\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0x8170f595b2b151e0e06052b79e81b80117f71181\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xa57a86ff03f536ccfce12ebfcd3361af421b82ed\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xad90a8b96fa17ae22566beb2eb5f3730771ba9ae\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xd604c626018d3924bfaa3b21e168451850b0fb14\"}],\"Comment\":\"Response from 205.251.199.2.\"}"
            ]
        );

        $jsonFileVerificationProvider = new JsonFileVerificationProvider($httpClient, new SignatureHash());
        $issuerResult = $jsonFileVerificationProvider->isValidIssuer($this->validData());

        $this->assertEquals('verified', $issuerResult);
    }

    public function test_issuer_from_json_is_invalid()
    {
        $httpClient = $this->createMock(AccredifyHttpClient::class);
        $httpClient->method('get')->with(self::GOOGLE_DNS_API, ['name' => 'ropstore.accredify.io', 'type' => 'TXT']
        )->willReturn(
            [
                'httpResponseStatus' => 200,
                'httpResponseContent' => "{\"Status\":0,\"TC\":false,\"RD\":true,\"RA\":true,\"AD\":false,\"CD\":false,\"Question\":[{\"name\":\"ropstore.accredify.io.\",\"type\":16}],\"Answer\":[{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x05b642ff12a4ae545357d82ba4f786f3aed84214#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x06a464971ea723177ef83df7b39dd63c373a6905#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x2FbBdba8BF963b1648e4755f587547Bd0Ea7685a#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x496a0f8348a092660c435cee0bb597b473ff8173#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x757cd434dd1e93d47a4c6ed7a1b31bd88d984b45#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x7c2f9fc979c13a3c86be64b8d2063f05ce799f6d#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x7f7b4ad63fbfd2b1bc5bd7ec269e22a53b28f6f3#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x8abde9e6aeeebfff9f2e24014582881a007ce74f#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x92557d2d818fea37ee8808219e77a93aef0f5e17#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xa979aeb39dd2307e060d7d11e1a446f358f0d21c#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xad4dbc3ad9dc3b7f52609d5b23f3c22e3e7cefa1#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xc04370e761f72e7d2985e274f914221efe51886e#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xed368d1c74cdc731e119c4ca4acdf65add9af735#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0x0B209E53234e5E9744d70509b74d66358df0bb27\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0x8170f595b2b151e0e06052b79e81b80117f71181\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xa57a86ff03f536ccfce12ebfcd3361af421b82ed\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xad90a8b96fa17ae22566beb2eb5f3730771ba9ae\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xd604c626018d3924bfaa3b21e168451850b0fb14\"}],\"Comment\":\"Response from 205.251.199.2.\"}"
            ]
        );

        $jsonFileVerificationProvider = new JsonFileVerificationProvider($httpClient, new SignatureHash());
        $issuerResult = $jsonFileVerificationProvider->isValidIssuer($this->invalidIssuerData());

        $this->assertEquals('invalid_issuer', $issuerResult);
    }

    public function test_signature_from_json_is_valid()
    {
        $httpClient = $this->createMock(AccredifyHttpClient::class);
        $httpClient->method('get')->with(self::GOOGLE_DNS_API, ['name' => 'ropstore.accredify.io', 'type' => 'TXT']
        )->willReturn(
            [
                'httpResponseStatus' => 200,
                'httpResponseContent' => "{\"Status\":0,\"TC\":false,\"RD\":true,\"RA\":true,\"AD\":false,\"CD\":false,\"Question\":[{\"name\":\"ropstore.accredify.io.\",\"type\":16}],\"Answer\":[{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x05b642ff12a4ae545357d82ba4f786f3aed84214#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x06a464971ea723177ef83df7b39dd63c373a6905#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x2FbBdba8BF963b1648e4755f587547Bd0Ea7685a#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x496a0f8348a092660c435cee0bb597b473ff8173#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x757cd434dd1e93d47a4c6ed7a1b31bd88d984b45#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x7c2f9fc979c13a3c86be64b8d2063f05ce799f6d#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x7f7b4ad63fbfd2b1bc5bd7ec269e22a53b28f6f3#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x8abde9e6aeeebfff9f2e24014582881a007ce74f#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x92557d2d818fea37ee8808219e77a93aef0f5e17#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xa979aeb39dd2307e060d7d11e1a446f358f0d21c#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xad4dbc3ad9dc3b7f52609d5b23f3c22e3e7cefa1#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xc04370e761f72e7d2985e274f914221efe51886e#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xed368d1c74cdc731e119c4ca4acdf65add9af735#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0x0B209E53234e5E9744d70509b74d66358df0bb27\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0x8170f595b2b151e0e06052b79e81b80117f71181\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xa57a86ff03f536ccfce12ebfcd3361af421b82ed\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xad90a8b96fa17ae22566beb2eb5f3730771ba9ae\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xd604c626018d3924bfaa3b21e168451850b0fb14\"}],\"Comment\":\"Response from 205.251.199.2.\"}"
            ]
        );

        $signatureHash = $this->createMock(SignatureHash::class);
        $signatureHash->method('getHashValue')->willReturn(
            "288f94aadadf486cfdad84b9f4305f7d51eac62db18376d48180cc1dd2047a0e"
        );

        $jsonFileVerificationProvider = new JsonFileVerificationProvider($httpClient, $signatureHash);
        $signatureResult = $jsonFileVerificationProvider->isValidSignature($this->validData());

        $this->assertEquals('verified', $signatureResult);
    }

    public function test_signature_from_json_is_invalid()
    {
        $httpClient = $this->createMock(AccredifyHttpClient::class);
        $httpClient->method('get')->with(self::GOOGLE_DNS_API, ['name' => 'ropstore.accredify.io', 'type' => 'TXT']
        )->willReturn(
            [
                'httpResponseStatus' => 200,
                'httpResponseContent' => "{\"Status\":0,\"TC\":false,\"RD\":true,\"RA\":true,\"AD\":false,\"CD\":false,\"Question\":[{\"name\":\"ropstore.accredify.io.\",\"type\":16}],\"Answer\":[{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x05b642ff12a4ae545357d82ba4f786f3aed84214#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x06a464971ea723177ef83df7b39dd63c373a6905#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x2FbBdba8BF963b1648e4755f587547Bd0Ea7685a#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x496a0f8348a092660c435cee0bb597b473ff8173#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x757cd434dd1e93d47a4c6ed7a1b31bd88d984b45#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x7c2f9fc979c13a3c86be64b8d2063f05ce799f6d#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x7f7b4ad63fbfd2b1bc5bd7ec269e22a53b28f6f3#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x8abde9e6aeeebfff9f2e24014582881a007ce74f#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x92557d2d818fea37ee8808219e77a93aef0f5e17#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xa979aeb39dd2307e060d7d11e1a446f358f0d21c#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xad4dbc3ad9dc3b7f52609d5b23f3c22e3e7cefa1#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xc04370e761f72e7d2985e274f914221efe51886e#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xed368d1c74cdc731e119c4ca4acdf65add9af735#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0x0B209E53234e5E9744d70509b74d66358df0bb27\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0x8170f595b2b151e0e06052b79e81b80117f71181\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xa57a86ff03f536ccfce12ebfcd3361af421b82ed\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xad90a8b96fa17ae22566beb2eb5f3730771ba9ae\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xd604c626018d3924bfaa3b21e168451850b0fb14\"}],\"Comment\":\"Response from 205.251.199.2.\"}"
            ]
        );

        $signatureHash = $this->createMock(SignatureHash::class);
        $signatureHash->method('getHashValue')->willReturn(
            "288f94aadadf486cfdad84b9f4305f7d51eac62db18376d48180cc1dd2047a0e"
        );

        $jsonFileVerificationProvider = new JsonFileVerificationProvider($httpClient, $signatureHash);
        $signatureResult = $jsonFileVerificationProvider->isValidSignature($this->invalidSignatureData());

        $this->assertEquals('invalid_signature', $signatureResult);
    }

    public function test_verify_json_file_success()
    {
        $httpClient = $this->createMock(AccredifyHttpClient::class);
        $httpClient->method('get')->with(self::GOOGLE_DNS_API, ['name' => 'ropstore.accredify.io', 'type' => 'TXT']
        )->willReturn(
            [
                'httpResponseStatus' => 200,
                'httpResponseContent' => "{\"Status\":0,\"TC\":false,\"RD\":true,\"RA\":true,\"AD\":false,\"CD\":false,\"Question\":[{\"name\":\"ropstore.accredify.io.\",\"type\":16}],\"Answer\":[{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x05b642ff12a4ae545357d82ba4f786f3aed84214#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x06a464971ea723177ef83df7b39dd63c373a6905#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x2FbBdba8BF963b1648e4755f587547Bd0Ea7685a#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x496a0f8348a092660c435cee0bb597b473ff8173#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x757cd434dd1e93d47a4c6ed7a1b31bd88d984b45#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x7c2f9fc979c13a3c86be64b8d2063f05ce799f6d#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x7f7b4ad63fbfd2b1bc5bd7ec269e22a53b28f6f3#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x8abde9e6aeeebfff9f2e24014582881a007ce74f#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x92557d2d818fea37ee8808219e77a93aef0f5e17#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xa979aeb39dd2307e060d7d11e1a446f358f0d21c#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xad4dbc3ad9dc3b7f52609d5b23f3c22e3e7cefa1#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xc04370e761f72e7d2985e274f914221efe51886e#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xed368d1c74cdc731e119c4ca4acdf65add9af735#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0x0B209E53234e5E9744d70509b74d66358df0bb27\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0x8170f595b2b151e0e06052b79e81b80117f71181\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xa57a86ff03f536ccfce12ebfcd3361af421b82ed\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xad90a8b96fa17ae22566beb2eb5f3730771ba9ae\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xd604c626018d3924bfaa3b21e168451850b0fb14\"}],\"Comment\":\"Response from 205.251.199.2.\"}"
            ]
        );

        $signatureHash = $this->createMock(SignatureHash::class);
        $signatureHash->method('getHashValue')->willReturn(
            "288f94aadadf486cfdad84b9f4305f7d51eac62db18376d48180cc1dd2047a0e"
        );

        $jsonFileVerificationProvider = new JsonFileVerificationProvider($httpClient, $signatureHash);
        $verificationResult = $jsonFileVerificationProvider->isValidDataFile($this->validData());

        $this->assertEquals('verified', $verificationResult);
    }

    public function test_verify_json_file_fail()
    {
        $httpClient = $this->createMock(AccredifyHttpClient::class);
        $httpClient->method('get')->with(self::GOOGLE_DNS_API, ['name' => 'ropstore.accredify.io', 'type' => 'TXT']
        )->willReturn(
            [
                'httpResponseStatus' => 200,
                'httpResponseContent' => "{\"Status\":0,\"TC\":false,\"RD\":true,\"RA\":true,\"AD\":false,\"CD\":false,\"Question\":[{\"name\":\"ropstore.accredify.io.\",\"type\":16}],\"Answer\":[{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x05b642ff12a4ae545357d82ba4f786f3aed84214#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x06a464971ea723177ef83df7b39dd63c373a6905#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x2FbBdba8BF963b1648e4755f587547Bd0Ea7685a#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x496a0f8348a092660c435cee0bb597b473ff8173#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x757cd434dd1e93d47a4c6ed7a1b31bd88d984b45#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x7c2f9fc979c13a3c86be64b8d2063f05ce799f6d#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x7f7b4ad63fbfd2b1bc5bd7ec269e22a53b28f6f3#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x8abde9e6aeeebfff9f2e24014582881a007ce74f#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0x92557d2d818fea37ee8808219e77a93aef0f5e17#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xa979aeb39dd2307e060d7d11e1a446f358f0d21c#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xad4dbc3ad9dc3b7f52609d5b23f3c22e3e7cefa1#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xc04370e761f72e7d2985e274f914221efe51886e#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts a=dns-did; p=did:ethr:0xed368d1c74cdc731e119c4ca4acdf65add9af735#controller; v=1.0;\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0x0B209E53234e5E9744d70509b74d66358df0bb27\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0x8170f595b2b151e0e06052b79e81b80117f71181\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xa57a86ff03f536ccfce12ebfcd3361af421b82ed\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xad90a8b96fa17ae22566beb2eb5f3730771ba9ae\"},{\"name\":\"ropstore.accredify.io.\",\"type\":16,\"TTL\":300,\"data\":\"openatts net=ethereum netId=3 addr=0xd604c626018d3924bfaa3b21e168451850b0fb14\"}],\"Comment\":\"Response from 205.251.199.2.\"}"
            ]
        );

        $signatureHash = $this->createMock(SignatureHash::class);
        $signatureHash->method('getHashValue')->willReturn(
            "288f94aadadf486cfdad84b9f4305f7d51eac62db18376d48180cc1dd2047a0e"
        );

        $jsonFileVerificationProvider = new JsonFileVerificationProvider($httpClient, $signatureHash);
        $verificationResult = $jsonFileVerificationProvider->isValidDataFile($this->invalidRecipientData());

        $this->assertEquals('invalid_recipient', $verificationResult);
    }

    private function validData(): array
    {
        return [
            'data' => [
                'id' => '63c79bd9303530645d1cca00',
                'name' => 'Certificate of Completion',
                'recipient' => [
                    'name' => 'Marty McFly',
                    'email' => 'marty.mcfly@gmail.com',
                ],
                'issuer' => [
                    'name' => 'Accredify',
                    'identityProof' => [
                        'type' => 'DNS-DID',
                        'key' => 'did:ethr:0x05b642ff12a4ae545357d82ba4f786f3aed84214#controller',
                        'location' => 'ropstore.accredify.io',
                    ],
                ],
                'issued' => '2022-12-23T00:00:00+08:00',
            ],
            'signature' => [
                'type' => 'SHA3MerkleProof',
                'targetHash' => '288f94aadadf486cfdad84b9f4305f7d51eac62db18376d48180cc1dd2047a0e',
            ],
        ];
    }

    private function invalidRecipientData(): array
    {
        return [
            'data' => [
                'recipient' => [
                    //    'name' => 'Marty McFly',
                    'email' => 'marty.mcfly@gmail.com',
                ],
            ],
        ];
    }

    private function invalidIssuerData(): array
    {
        return [
            'data' => [
                'issuer' => [
                    'name' => 'Accredify',
                    'identityProof' => [
                        'type' => 'DNS-DID',
                        'key' => 'did:ethr:0x05b642ff12a#controller',
                        'location' => 'ropstore.accredify.io',
                    ],
                ],
            ],
        ];
    }

    private function invalidSignatureData(): array
    {
        return [
            'data' => [
                'id' => '63c79bd9303530645d1cca00',
                'name' => 'Certificate of Completion',
                'recipient' => [
                    'name' => 'Marty McFly',
                    'email' => 'marty.mcfly@gmail.com',
                ],
                'issuer' => [
                    'name' => 'Accredify',
                    'identityProof' => [
                        'type' => 'DNS-DID',
                        'key' => 'did:ethr:0x05b642ff12a4ae545357d82ba4f786f3aed84214#controller',
                        'location' => 'ropstore.accredify.io',
                    ],
                ],
                'issued' => '2022-12-23T00:00:00+08:00',
            ],
            'signature' => [
                'type' => 'SHA3MerkleProof',
                'targetHash' => '288f94aada',
            ],
        ];
    }
}
