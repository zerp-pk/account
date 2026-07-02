<?php

namespace Zerp\Account\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Zerp\Account\Models\BankTransfer;

class UpdateBankTransfer
{
    use Dispatchable;

    public function __construct(
        public Request $request,
        public BankTransfer $bankTransfer
    ) {}
}