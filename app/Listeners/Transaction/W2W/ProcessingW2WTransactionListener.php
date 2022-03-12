<?php

namespace App\Listeners\Transaction\W2W;

use App\Events\Transaction\W2W\OpenW2WTransactionEvent;
use App\Jobs\Transaction\W2W\ProcessingW2WTransactionJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessingW2WTransactionListener
{
    /**
     * @param OpenW2WTransactionEvent $event
     */
    public function handle(OpenW2WTransactionEvent $event)
    {
        dispatch(new ProcessingW2WTransactionJob($event->transaction));
    }
}
