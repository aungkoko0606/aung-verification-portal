<?php
declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Http\Controllers\VerificationController;
use App\Http\Requests\FileVerificationRequest;
use App\Services\Verification\VerificationService;
use Illuminate\Http\Response;
use PHPUnit\Framework\TestCase;

class VerificationControllerTest extends TestCase
{

    public function test_verification_success_response()
    {
        $fileVerificationRequest = $this->createMock(FileVerificationRequest::class);
        $verificationService = $this->createMock(VerificationService::class);
        $verificationService->method('verifyDataFromRequest')->with($fileVerificationRequest)->willReturn([
            'issuer' => 'Hogwarts', 'result' => 'verified'
        ]);
        $verificationController = new VerificationController();
        $verificationResponse = $verificationController->__invoke($fileVerificationRequest, $verificationService);
        $this->assertEquals(Response::HTTP_OK, $verificationResponse->getStatusCode());
        $this->assertStringContainsString('verified', $verificationResponse->getContent());
    }
}
