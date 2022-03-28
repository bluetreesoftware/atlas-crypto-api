<?php

namespace App\Jobs\Transaction\W2W;

use App\Models\Transaction;
use App\Services\Transaction\W2W\ProcessingW2WTransactionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessingW2WTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected Transaction $transaction
    ) {
        $this->onQueue('transactions_w2w');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new ProcessingW2WTransactionService($this->transaction))->processing();
    }
}
