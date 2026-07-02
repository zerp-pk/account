<?php

namespace Zerp\Account\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Zerp\Account\Services\BankTransactionsService;
use Zerp\Account\Services\JournalService;
use Zerp\DairyCattleManagement\Events\UpdateDairyCattlePaymentStatus;

class UpdateDairyCattlePaymentStatusListener
{
    protected $journalService;
    protected $bankTransactionsService;

    public function __construct(JournalService $journalService, BankTransactionsService $bankTransactionsService)
    {
        $this->journalService = $journalService;
        $this->bankTransactionsService = $bankTransactionsService;
    }

    public function handle(UpdateDairyCattlePaymentStatus $event)
    {
        if(Module_is_active('Account'))
        {
            $this->bankTransactionsService->createDairyCattlePayment($event->dairyCattlePayment);
            $this->journalService->createDairyCattlePaymentJournal($event->dairyCattlePayment);
        }
    }
}
