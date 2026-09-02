<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminWalletAdjustRequest;
use App\Http\Resources\WalletResource;
use App\Http\Resources\WalletTransactionResource;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\Admin\AdminAuditService;
use App\Services\Wallet\WalletService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminWalletController extends Controller
{
    public function __construct(
        protected WalletService $walletService,
        protected AdminAuditService $auditService
    ) {}

    /**
     * List all platform wallet transactions
     */
    public function transactions(Request $request): JsonResponse
    {
        $query = WalletTransaction::query()->with('user');

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(25);

        return ApiResponse::paginated(WalletTransactionResource::collection($transactions));
    }

    /**
     * Admin manual wallet coin adjustment with audit trail
     */
    public function adjust(AdminWalletAdjustRequest $request, User $user): JsonResponse
    {
        $amount = (int) $request->input('amount');
        $reason = $request->input('reason');
        $admin = $request->user('admin');

        $transaction = null;

        if ($amount > 0) {
            $transaction = $this->walletService->credit(
                $user,
                $amount,
                'admin_adjustment',
                $admin->id,
                'AdminUser',
                "Admin adjustment: {$reason}",
                ['admin_id' => $admin->id, 'reason' => $reason]
            );
        } else {
            $transaction = $this->walletService->debit(
                $user,
                abs($amount),
                'admin_adjustment',
                $admin->id,
                'AdminUser',
                "Admin adjustment: {$reason}",
                ['admin_id' => $admin->id, 'reason' => $reason]
            );
        }

        $this->auditService->log(
            $admin,
            'wallet_adjustment',
            'User',
            $user->id,
            [
                'amount' => $amount,
                'reason' => $reason,
                'transaction_id' => $transaction->id,
                'balance_before' => $transaction->balance_before,
                'balance_after' => $transaction->balance_after,
            ],
            $request
        );

        $wallet = $this->walletService->getWallet($user);

        return ApiResponse::success([
            'transaction' => new WalletTransactionResource($transaction),
            'wallet' => new WalletResource($wallet),
        ], 'Wallet balance adjusted successfully.');
    }
}
