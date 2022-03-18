<?php

namespace App\Http\Controllers\Consumer\Auth;

use App\Http\Requests\Consumer\Auth\SignInRequest;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class SignInController
{
    /**
     * @param SignInRequest $request
     * @return JsonResponse
     */
    public function __invoke(SignInRequest $request): JsonResponse
    {
        $account = Account::where('email', $request->email)->first();

        if ($account && Hash::check($request->password, $account->password)) {
            $token = $account->createToken($request->header('User-Agent'))->plainTextToken;

            return responder()->success(['token' => $token])->respond();
        }

        return responder()->error()->respond(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
