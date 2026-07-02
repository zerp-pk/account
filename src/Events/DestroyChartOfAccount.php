<?php

namespace Zerp\Account\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Zerp\Account\Models\ChartOfAccount;

class DestroyChartOfAccount
{
    use Dispatchable;

    public function __construct(
        public ChartOfAccount $chartofaccount
    ) {}
}
