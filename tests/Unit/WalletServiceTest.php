<?php

namespace Tests\Unit;

use App\Exceptions\ApiException;
use App\Exceptions\InsufficientBalanceException;
use App\Models\User;
use App\Models\Wallet;
use App\Services\Wallet\WalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletServiceTest extends TestCase
{
    use RefreshDatabase;

    protected WalletService $walletService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->walletService = app(WalletService::class);
    }

    public function test_wallet_can_be_credited_atomically(): void
    {
        $user = User::factory()->create();

        $transaction = $this->walletService->credit($user, 100, 'purchase', null, null, 'Test credit');

        $this->assertEquals(100, $transaction->amount);
        $this->assertEquals(0, $transaction->balance_before);
        $this->assertEquals(100, $transaction->balance_after);

        $wallet = $this->walletService->getWallet($user);
        $this->assertEquals(100, $wallet->balance);
    }

    public function test_wallet_can_be_debited_successfully(): void
    {
        $user = User::factory()->create();
        $this->walletService->credit($user, 100, 'purchase');

        $debitTransaction = $this->walletService->debit($user, 40, 'spend', null, null, 'Test debit');

        $this->assertEquals(-40, $debitTransaction->amount);
        $this->assertEquals(100, $debitTransaction->balance_before);
        $this->assertEquals(60, $debitTransaction->balance_after);

        $wallet = $this->walletService->getWallet($user);
        $this->assertEquals(60, $wallet->balance);
    }

    public function test_wallet_throws_insufficient_balance_exception_when_overdrawn(): void
    {
        $user = User::factory()->create();
        $this->walletService->credit($user, 20, 'purchase');

        $this->expectException(InsufficientBalanceException::class);
        $this->walletService->debit($user, 50, 'spend');
    }
}
