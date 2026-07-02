<?php

namespace Zerp\Account\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Account\Models\CreditNote;

class DestroyCreditNote
{

    use Dispatchable;

    public function __construct(
        public CreditNote $creditNote
    ) {}
}