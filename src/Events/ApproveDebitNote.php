<?php

namespace Zerp\Account\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Account\Models\DebitNote;

class ApproveDebitNote
{
    use Dispatchable;

    public function __construct(
        public DebitNote $debitNote
    ) {}
}
