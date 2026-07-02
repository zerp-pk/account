<?php

namespace Zerp\Account\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Zerp\Account\Models\BankAccount;

class DestroyBankAccount
{
    use Dispatchable;

    public function __construct(
        public BankAccount $bankAccount
    ) {}
}