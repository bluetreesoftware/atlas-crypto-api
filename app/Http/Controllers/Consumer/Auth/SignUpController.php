<?php

namespace App\Http\Controllers\Consumer\Auth;

use App\Http\Requests\Consumer\Auth\SignUpRequest;
use App\Models\Account;
use App\Services\Account\CreateAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class SignUpController
{
    /**
     * @param SignUpRequest $request
     * @param CreateAccountService $createAccountService
     * @return JsonResponse
     */
    public function __invoke(SignUpRequest $request, CreateAccountService $createAccountService): JsonResponse
    {
        $account = $createAccountService->fromRequest($request);

        return responder()->success($account)->respond(Response::HTTP_CREATED);
    }
}
