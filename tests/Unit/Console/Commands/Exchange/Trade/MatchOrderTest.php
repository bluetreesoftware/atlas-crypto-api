<?php

namespace Tests\Unit\Console\Commands\Exchange\Trade;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_match_sale_limit_order()
    {
        $this->assertTrue(true);
    }
}
