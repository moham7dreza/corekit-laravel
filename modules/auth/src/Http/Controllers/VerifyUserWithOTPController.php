<?php

declare(strict_types=1);

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiJsonResponse;
use Illuminate\Http\JsonResponse;
use Modules\Auth\Data\TransferObjects\VerifyOtpRequestData;
use Modules\Auth\Http\Requests\VerifyOtpRequest;
use Modules\Auth\Services\VerifyOtpService;
use Throwable;

final class VerifyUserWithOTPController extends Controller
{
    public function __construct(
        private readonly VerifyOtpService $verifyOtpService
    ) {}

    public function __invoke(VerifyOtpRequest $request): JsonResponse
    {
        try {
            $data = VerifyOtpRequestData::from($request->validated());

            $result = $this->verifyOtpService->handle($data);

            return ApiJsonResponse::success(
                [
                    'user' => $result->user->toArray(),
                    'token' => $result->token,
                ],
                message: $result->message
            );

        } catch (Throwable $e) {
            return ApiJsonResponse::error(
                $e->getCode(),
                $e->getMessage()
            );
        }
    }
}
