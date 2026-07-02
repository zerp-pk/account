<?php

namespace Zerp\Account\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Zerp\Account\Models\ChartOfAccount;

class UpdateChartOfAccount
{
    use Dispatchable;

    public function __construct(
        public Request $request,
        public ChartOfAccount $chartofaccount
    ) {}
}
