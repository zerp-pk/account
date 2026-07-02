<?php

namespace Zerp\Account\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Account\Models\Revenue;

class DestroyRevenue
{
    use Dispatchable;

    public function __construct(
        public Revenue $revenue
    ) {}
}
