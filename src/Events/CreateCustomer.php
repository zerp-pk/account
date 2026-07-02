<?php

namespace Zerp\Account\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Zerp\Account\Models\Customer;

class CreateCustomer
{
    use Dispatchable;

    public function __construct(
        public Request $request,
        public Customer $customer
    ) {}
}