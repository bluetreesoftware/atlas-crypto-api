<?php

namespace App\Http\Controllers\Consumer\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AccountController
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return responder()->success(Auth::user())->respond();
    }
}
