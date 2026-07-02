<?php

namespace Zerp\Account\Listeners;

use Zerp\Account\Models\BankAccount;
use Zerp\Hrm\Events\PaySalary;
use Zerp\Account\Services\JournalService;
use Zerp\Account\Services\BankTransactionsService;
use Zerp\Account\Models\ChartOfAccount;

class PaySalaryListener
{
    protected $journalService;
    protected $bankTransactionsService;

    public function __construct(JournalService $journalService, BankTransactionsService $bankTransactionsService)
    {
        $this->journalService = $journalService;
        $this->bankTransactionsService = $bankTransactionsService;
    }

    public function handle(PaySalary $event)
    {
        if (Module_is_active('Account'))
        {
            $this->journalService->createPayrollJournal($event->payrollEntry);
            $this->bankTransactionsService->createPayrollPayment($event->payrollEntry);
        }
    }
}

