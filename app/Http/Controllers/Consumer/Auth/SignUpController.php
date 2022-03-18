<?php

namespace App\Http\Controllers\Consumer\Auth;

use App\Http\Requests\Consumer\Auth\SignUpRequest;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class SignUpController
{
    /**
     * @param SignUpRequest $request
     * @return JsonResponse
     */
    public function __invoke(SignUpRequest $request): JsonResponse
    {
        return responder()->success(Account::create($request->validated()))->respond(Response::HTTP_CREATED);
    }
}
