<?php

namespace Zerp\Account\Listeners;

use Zerp\Account\Models\BankAccount;
use Zerp\Pos\Events\CreatePos;
use Zerp\Account\Services\JournalService;
use Zerp\Account\Services\BankTransactionsService;
use Zerp\Account\Models\ChartOfAccount;

class CreatePosListener
{
    protected $journalService;
    protected $bankTransactionsService;

    public function __construct(JournalService $journalService, BankTransactionsService $bankTransactionsService)
    {
        $this->journalService = $journalService;
        $this->bankTransactionsService = $bankTransactionsService;
    }

    public function handle(CreatePos $event)
    {
        if (Module_is_active('Account')) {

            $bankAccount = BankAccount::where('id', $event->posSale->bank_account_id)
                ->where('created_by', creatorId())
                ->first();

            if ($bankAccount) {
                $this->bankTransactionsService->createPosPayment($event->posSale, $bankAccount->id);
            }

            $this->journalService->createPosJournal($event->posSale);
            $this->journalService->createPosCOGSJournal($event->posSale);
        }
    }
}
