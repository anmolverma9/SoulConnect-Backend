<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Models\CoinPurchase;
use App\Models\GiftCatalog;
use App\Models\MatchModel;
use App\Models\Report;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Wallet;
use App\Support\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Get admin dashboard summary metrics
     */
    public function index(): JsonResponse
    {
        $today = Carbon::today();

        $stats = [
            'users' => [
                'total' => User::count(),
                'active' => User::where('status', 'active')->count(),
                'suspended' => User::where('status', 'suspended')->count(),
                'banned' => User::where('status', 'banned')->count(),
                'new_today' => User::where('created_at', '>=', $today)->count(),
            ],
            'matches' => [
                'total' => MatchModel::count(),
                'new_today' => MatchModel::where('created_at', '>=', $today)->count(),
            ],
            'calls' => [
                'total' => Call::count(),
                'completed' => Call::where('status', 'ended')->count(),
                'total_minutes' => (int) ceil(Call::where('status', 'ended')->sum('duration_seconds') / 60),
            ],
            'subscriptions' => [
                'active' => Subscription::whereIn('status', ['active', 'grace_period'])->where('ends_at', '>', now())->count(),
            ],
            'finance' => [
                'total_coin_sales_usd' => (float) CoinPurchase::where('status', 'verified')->join('coin_packages', 'coin_purchases.package_id', '=', 'coin_packages.id')->sum('coin_packages.price'),
                'total_coins_in_wallets' => (int) Wallet::sum('balance'),
            ],
            'reports' => [
                'pending' => Report::where('status', 'pending')->count(),
                'reviewing' => Report::where('status', 'reviewing')->count(),
                'resolved' => Report::where('status', 'resolved')->count(),
            ],
        ];

        return ApiResponse::success($stats);
    }

    /**
     * List recent voice & video calls
     */
    public function calls(Request $request): JsonResponse
    {
        $calls = Call::with(['caller.profile', 'receiver.profile'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return ApiResponse::success($calls);
    }

    /**
     * List gift catalog
     */
    public function gifts(): JsonResponse
    {
        $gifts = GiftCatalog::orderBy('sort_order', 'asc')->get();
        return ApiResponse::success($gifts);
    }

    /**
     * Create new virtual gift
     */
    public function storeGift(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:64',
            'icon_url' => 'required|string',
            'coin_price' => 'required|integer|min:1',
            'animation_type' => 'nullable|string|max:32',
            'category' => 'nullable|string|max:32',
            'is_active' => 'boolean',
        ]);

        $gift = GiftCatalog::create($validated);
        return ApiResponse::success($gift, 'Gift created successfully.', 201);
    }

    /**
     * Delete virtual gift
     */
    public function destroyGift(GiftCatalog $gift): JsonResponse
    {
        $gift->delete();
        return ApiResponse::success(null, 'Gift deleted.');
    }
}
