<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Models\CoinPurchase;
use App\Models\MatchModel;
use App\Models\Report;
use App\Models\Subscription;
use App\Models\User;
use App\Support\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

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
            ],
            'subscriptions' => [
                'active' => Subscription::whereIn('status', ['active', 'grace_period'])->where('ends_at', '>', now())->count(),
            ],
            'revenue' => [
                'total_coin_sales' => (float) CoinPurchase::where('status', 'verified')->join('coin_packages', 'coin_purchases.package_id', '=', 'coin_packages.id')->sum('coin_packages.price'),
            ],
            'reports' => [
                'pending' => Report::where('status', 'pending')->count(),
                'reviewing' => Report::where('status', 'reviewing')->count(),
            ],
        ];

        return ApiResponse::success($stats);
    }
}
