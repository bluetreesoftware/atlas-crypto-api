<?php

namespace App\Http\Controllers\Consumer;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;

class ConsumerController extends Controller
{
    /**
     * @var Account
     */
    protected Account $account;

    public function __construct()
    {
        $this->account = Account::find(Auth::id());
    }
}
