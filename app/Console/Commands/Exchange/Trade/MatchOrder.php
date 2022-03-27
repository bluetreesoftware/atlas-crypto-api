<?php

namespace App\Console\Commands\Exchange\Trade;

use App\Enums\Exchange\OrderActionEnum;
use App\Enums\Exchange\OrderStatusEnum;
use App\Models\Exchange\Order;
use App\Models\Exchange\Trade;
use App\Services\Exchange\Order\ExecuteOrdersService;
use App\Services\Exchange\Trade\GetExecutableLimitOrderService;
use App\Services\Exchange\Trade\GetExecutableMarketOrderService;
use App\Services\Transaction\W2W\OpenW2WTransactionService;
use Illuminate\Console\Command;

class MatchOrder extends Command
{
    private const ACTION_SALE = 'sale';

    private const ACTION_BUY = 'buy';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange:trade:match-orders {--trade-id=} {--action=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @return OrderActionEnum
     */
    protected function getMarketAction(): OrderActionEnum
    {
        return $this->option('action') === self::ACTION_BUY
            ? OrderActionEnum::Sale
            : OrderActionEnum::Buy;
    }

    /**
     * @return OrderActionEnum
     */
    protected function getLimitAction(): OrderActionEnum
    {
        return $this->option('action') === self::ACTION_BUY
            ? OrderActionEnum::Buy
            : OrderActionEnum::Sale;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /** @var Trade $trade */
        $trade = Trade::find($this->option('trade-id'));
        $limitAction = $this->getLimitAction();
        $marketAction = $this->getMarketAction();

        while (true) {
            $limitOrder = (new GetExecutableLimitOrderService($trade, $limitAction))->get();
            if ($limitOrder) {
                $marketOrder = (new GetExecutableMarketOrderService($trade, $marketAction))->get();

                if ($marketOrder) {
                    $this->line('limit order: ' . $limitOrder->id);
                    $this->line('market order: ' . $marketOrder->id);

                    (new ExecuteOrdersService($trade, $limitOrder, $marketOrder, $limitAction))->execute();
                }
            } else {
                $this->line('');
                sleep(1);
            }
        }
    }
}
