<?php

namespace Zerp\Account\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Zerp\Account\Models\VendorPayment;

class DestroyVendorPayment
{
    use Dispatchable;

    public function __construct(
        public VendorPayment $vendorPayment
    ) {}
}