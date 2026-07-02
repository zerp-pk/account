<?php

namespace Zerp\Account\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Account\Models\ExpenseCategories;

class DestroyExpenseCategories
{
    use Dispatchable;

    public function __construct(
        public ExpenseCategories $expenseCategories
    ) {}
}
