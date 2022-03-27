<?php

namespace App\Enums\Transaction;

enum TransactionTypeEnum: int
{
    case W2W = 0; //wallet-to-wallet
    case P2P = 1; //peer-to-peer
    case P2W = 2; //peer-to-wallet
    case O2W = 3; //order-to-wallet
}
