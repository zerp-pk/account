<?php

namespace Zerp\Account\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Account\Models\JournalEntry;

class UpdateBudgetSpending
{
    use Dispatchable;

    public function __construct(
        public JournalEntry $journalEntry,
    ) {}
}
