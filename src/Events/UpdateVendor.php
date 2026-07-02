<?php

namespace Zerp\Account\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Zerp\Account\Models\Vendor;

class UpdateVendor
{
    use Dispatchable;

    public function __construct(
        public Request $request,
        public Vendor $vendor
    ) {}
}