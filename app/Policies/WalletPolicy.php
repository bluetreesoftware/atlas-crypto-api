<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\Wallet;
use Illuminate\Auth\Access\HandlesAuthorization;

class WalletPolicy
{
    use HandlesAuthorization;

    /**
     * @param Account $account
     * @return bool
     */
    public function viewAny(Account $account): bool
    {
        return true;
    }

    /**
     * @param Account $account
     * @param Wallet $wallet
     * @return bool
     */
    public function view(Account $account, Wallet $wallet): bool
    {
        return $account->id === $wallet->account_id;
    }

    /**
     * @param Account $account
     * @return bool
     */
    public function create(Account $account): bool
    {
        return true;
    }
}
