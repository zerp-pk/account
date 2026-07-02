<?php

namespace Zerp\Account\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Zerp\Account\Services\BankTransactionsService;
use Zerp\Account\Services\JournalService;
use Zerp\Commission\Events\UpdateCommissionPaymentStatus;

class UpdateCommissionPaymentStatusListener
{
    protected $journalService;
    protected $bankTransactionsService;


    public function __construct(JournalService $journalService ,BankTransactionsService $bankTransactionsService)
    {
        $this->journalService = $journalService;
        $this->bankTransactionsService = $bankTransactionsService;
    }

    public function handle(UpdateCommissionPaymentStatus $event)
    {
        if (Module_is_active('Account')) {
            $this->journalService->createCommissionPaymentJournal($event->commissionPayment);
            $this->bankTransactionsService->createCommissionPayment($event->commissionPayment);

        }
    }
}
