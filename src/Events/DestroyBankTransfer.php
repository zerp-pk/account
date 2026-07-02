<?php

namespace Zerp\Account\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Zerp\Account\Models\BankTransfer;

class DestroyBankTransfer
{
    use Dispatchable;

    public function __construct(
        public BankTransfer $bankTransfer
    ) {}
}