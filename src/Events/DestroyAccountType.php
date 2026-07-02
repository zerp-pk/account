<?php

namespace Zerp\Account\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Account\Models\AccountType;

class DestroyAccountType
{
    use Dispatchable;

    public function __construct(
        public AccountType $accounttype
    ) {}
}
