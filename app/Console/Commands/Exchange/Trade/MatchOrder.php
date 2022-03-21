<?php

namespace App\Console\Commands\Exchange\Trade;

use App\Enums\Exchange\OrderStatusEnum;
use App\Models\Exchange\Order;
use App\Models\Exchange\Trade;
use App\Services\Exchange\Order\ExecuteOrderService;
use App\Services\Transaction\W2W\OpenW2WTransactionService;
use Illuminate\Console\Command;

class MatchOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange:trade:match-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /** @var Trade $trade */
        $trade = Trade::first();

        while (true) {
            /** @var Order $marketOrder */
            $marketOrder = Order::market()->buy()->open()->first();
            $needQuantity = $marketOrder->quantity;
            $this->line("processing market order: {$marketOrder->id} | {$marketOrder->quantity}");
            while (true) {
                /** @var Order $limitOrder */
                $limitOrder = Order::limit()
                    ->sale()
                    ->open()
                    ->where('status', 0)
                    ->orderBy('price', 'asc')
                    ->orderBy('quantity', 'asc')
                    ->orderBy('created_at', 'asc')
                    ->first();


                $reminders = (new ExecuteOrderService($trade, $limitOrder, $marketOrder))->execute();

                $lotPrice = $limitOrder->price / $trade->baseCurrency->accuracy;

                if ($limitOrder->actual_quantity < $needQuantity) {
                    $needQuantity -= $limitOrder->quantity;

                    $limitOrder->update([
                        'status' => OrderStatusEnum::Completed,
                        'actual_quantity' => 0
                    ]);

                    $this->line("close limit order: {$limitOrder->id} | {$limitOrder->quantity} [PART-ALL]");

                } elseif ($limitOrder->actual_quantity === $needQuantity) {
                    $needQuantity = 0;
                    $limitOrder->update([
                        'status' => OrderStatusEnum::Completed,
                        'actual_quantity' => 0
                    ]);


                    $this->line("close limit order: {$limitOrder->id} | {$limitOrder->quantity} [ALL-ALl]");
                } elseif ($limitOrder->actual_quantity > $needQuantity) {
                    $limitOrder->update([
                        'actual_quantity' => $limitOrder->actual_quantity - $needQuantity
                    ]);

                    $needQuantity = 0;

                    $this->line("close limit order: {$limitOrder->id} | {$limitOrder->quantity} [ALL-PART]");
                }

                if ($needQuantity === 0) {
                    $marketOrder->update([
                        'status' => OrderStatusEnum::Completed
                    ]);

                    $this->line('close market order: ' . $marketOrder->id);
                    break;
                }
            }

        }

        return 0;
    }
}
