<?php

namespace Zerp\Account\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Account\Models\Expense;

class DestroyExpense
{
    use Dispatchable;

    public function __construct(
        public Expense $expense
    ) {}
}
