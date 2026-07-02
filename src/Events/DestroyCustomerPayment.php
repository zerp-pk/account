<?php

namespace Zerp\Account\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Account\Models\CustomerPayment;

class DestroyCustomerPayment
{
    use Dispatchable;

    public function __construct(
        public CustomerPayment $customerPayment
    ) {}
}
