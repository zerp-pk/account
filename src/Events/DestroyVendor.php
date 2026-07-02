<?php

namespace Zerp\Account\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Zerp\Account\Models\Vendor;

class DestroyVendor
{
    use Dispatchable;

    public function __construct(
        public Vendor $vendor
    ) {}
}