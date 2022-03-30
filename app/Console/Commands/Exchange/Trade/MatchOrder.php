<?php

namespace App\Console\Commands\Exchange\Trade;

use App\Models\Exchange\Trade;
use Illuminate\Console\Command;
use App\Enums\Exchange\OrderActionEnum;
use App\Services\Exchange\Trade\MatchOrderService;

class MatchOrder extends Command
{
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
     */
    public function handle()
    {
        /** @var Trade $trade */
        $trade = Trade::find($this->option('trade-id'));
        $limitAction = $this->getLimitAction();
        $marketAction = $this->getMarketAction();

        $tick = 0;
        while (true) {
            $result = (new MatchOrderService($trade, $limitAction, $marketAction))->match();

            if (!$result) {
                sleep(10);
            }
            $this->info('tick: ' . ++$tick);
        }
    }
}
