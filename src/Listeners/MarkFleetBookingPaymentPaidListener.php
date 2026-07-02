<?php

namespace Zerp\Account\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Zerp\Account\Services\BankTransactionsService;
use Zerp\Account\Services\JournalService;
use Workdo\Fleet\Events\MarkFleetBookingPaymentPaid;

class MarkFleetBookingPaymentPaidListener
{
    protected $journalService;
    protected $bankTransactionsService;

    public function __construct(JournalService $journalService, BankTransactionsService $bankTransactionsService)
    {
        $this->journalService = $journalService;
        $this->bankTransactionsService = $bankTransactionsService;
    }

    public function handle(MarkFleetBookingPaymentPaid $event)
    {
        if(Module_is_active('Account'))
        {
            $this->bankTransactionsService->createMarkFleetBookingPayment($event->payment);
            $this->journalService->createMarkFleetBookingPaymentJournal($event->payment);
        }
    }
}
