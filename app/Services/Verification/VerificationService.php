<?php

namespace App\Services\Verification;

use App\Http\Requests\FileVerificationRequest;
use App\Models\VerificationLog;
use App\Repositories\EloquentVerificationLogRepository;
use Illuminate\Support\Facades\Auth;

class VerificationService
{
    private FileVerificationProviderFactory $fileVerificationProviderFactory;

    private EloquentVerificationLogRepository $verificationLogRepository;

    public function __construct(FileVerificationProviderFactory $fileVerificationProviderFactory, EloquentVerificationLogRepository $verificationLogRepository)
    {
        $this->fileVerificationProviderFactory = $fileVerificationProviderFactory;
        $this->verificationLogRepository = $verificationLogRepository;
    }

    public function verifyDataFromRequest(FileVerificationRequest $request): array
    {
        $validated = $request->validated();
        $verificationData = json_decode(file_get_contents($validated['verification_file']), true);

        $jsonFileVerificationProvider = $this->fileVerificationProviderFactory->create('json');
        $verificationResult = $jsonFileVerificationProvider->isValidDataFile($verificationData);
        $this->saveVerificationLog(Auth::user()->id, $verificationResult);

        return ['issuer' => $verificationData['data']['issuer']['name'] ?? '', 'result' => $verificationResult ];
    }

    private function saveVerificationLog(int $userId, string $verificationResult, string $fileType='json') : void
    {
        $verificationLog = new VerificationLog();
        $verificationLog->user_id = $userId;
        $verificationLog->verification_result = $verificationResult;
        $verificationLog->file_type = $fileType;
        $this->verificationLogRepository->save($verificationLog);
    }

}
