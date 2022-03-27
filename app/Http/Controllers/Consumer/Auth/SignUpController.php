<?php

namespace App\Http\Controllers\Consumer\Auth;

use App\Http\Requests\Consumer\Auth\SignUpRequest;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class SignUpController
{
    /**
     * @param SignUpRequest $request
     * @return JsonResponse
     */
    public function __invoke(SignUpRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        return responder()->success(Account::create($data))->respond(Response::HTTP_CREATED);
    }
}
