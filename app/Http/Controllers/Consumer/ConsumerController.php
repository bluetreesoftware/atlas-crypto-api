<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Models\Account;

class ConsumerController extends Controller
{
    /**
     * @var Account
     */
    protected Account $account;

    public function __construct()
    {
        $this->account = Account::find(auth('sanctum')->user()->id);
    }
}
