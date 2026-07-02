<?php

namespace Zerp\Account\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Zerp\Account\Models\ExpenseCategories;

class CreateExpenseCategories
{
    use Dispatchable;

    public function __construct(
        public Request $request,
        public ExpenseCategories $expenseCategories
    ) {}
}
