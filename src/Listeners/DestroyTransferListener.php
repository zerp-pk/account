<?php

namespace Zerp\Account\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Zerp\Account\Services\JournalService;

class DestroyTransferListener
{
    protected $journalService;

    public function __construct(JournalService $journalService)
    {
        $this->journalService = $journalService;
    }

    public function handle($event)
    {
        if(Module_is_active('Account'))
        {
            $this->journalService->deleteStockTransferJournal($event->transfer->id);
        }
    }
}
