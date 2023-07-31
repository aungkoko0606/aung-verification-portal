<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\FileVerificationRequest;
use App\Services\Verification\VerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class VerificationController extends Controller
{
    public function __invoke(FileVerificationRequest $request, VerificationService $verificationService)
    {
        try {
            $verificationResult = $verificationService->verifyDataFromRequest($request);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'errors' => [
                        'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'title' => 'Internal Server Error',
                        'detail' => 'Please try again later'
                    ]
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new JsonResponse(['data' => $verificationResult], Response::HTTP_OK);
    }

}
