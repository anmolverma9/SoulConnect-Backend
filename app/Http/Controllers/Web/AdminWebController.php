<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use App\Models\AdminUser;
use App\Models\Call;
use App\Models\CoinPackage;
use App\Models\CoinPurchase;
use App\Models\GiftCatalog;
use App\Models\MatchModel;
use App\Models\Report;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\Admin\AdminAuditService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminWebController extends Controller
{
    public function __construct(
        protected AdminAuditService $auditService
    ) {}

    /**
     * Show Admin Login Page
     */
    public function login(): View|RedirectResponse
    {
        if (Auth::guard('admin_web')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Handle Admin Login
     */
    public function handleLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $admin = AdminUser::where('email', $credentials['email'])->first();

        if (! $admin || ! Hash::check($credentials['password'], $admin->password)) {
            return back()->withInput()->with('error', 'Invalid admin email or password.');
        }

        if (! $admin->is_active) {
            return back()->withInput()->with('error', 'This admin account has been deactivated.');
        }

        Auth::guard('admin_web')->login($admin, $request->boolean('remember', true));
        $admin->update(['last_login_at' => Carbon::now()]);

        return redirect()->intended(route('admin.dashboard'))->with('success', "Welcome back, {$admin->name}!");
    }

    /**
     * Handle Admin Logout
     */
    public function logout(): RedirectResponse
    {
        Auth::guard('admin_web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'You have been logged out.');
    }

    /**
     * Dashboard Overview
     */
    public function dashboard(): View
    {
        $today = Carbon::today();

        $stats = [
            'total_users' => User::count(),
            'new_users_today' => User::where('created_at', '>=', $today)->count(),
            'total_matches' => MatchModel::count(),
            'total_calls' => Call::count(),
            'call_minutes' => (int) ceil(Call::where('status', 'ended')->sum('duration_seconds') / 60),
            'active_subs' => Subscription::whereIn('status', ['active', 'grace_period'])->where('ends_at', '>', now())->count(),
            'coin_sales_usd' => (float) CoinPurchase::where('status', 'verified')->join('coin_packages', 'coin_purchases.package_id', '=', 'coin_packages.id')->sum('coin_packages.price'),
            'circulating_coins' => (int) Wallet::sum('balance'),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'recent_users' => User::with('profile', 'wallet')->orderBy('created_at', 'desc')->limit(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Users & Profiles Management
     */
    public function users(Request $request): View
    {
        $query = User::query()->with(['profile', 'wallet', 'activeSubscription.plan']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('profile', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show Dedicated User Profile Dossier
     */
    public function showUser(User $user): View
    {
        $user->load(['profile', 'photos', 'preferences', 'wallet.transactions', 'activeSubscription.plan', 'devices']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Update User Info
     */
    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'status' => 'required|string|in:active,suspended,banned,deleted',
            'gender' => 'nullable|string|in:male,female,non_binary,other',
            'city' => 'nullable|string|max:100',
            'occupation' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:1000',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'status' => $validated['status'],
        ]);

        if ($user->profile) {
            $user->profile->update([
                'name' => $validated['name'],
                'gender' => $validated['gender'] ?? $user->profile->gender,
                'city' => $validated['city'] ?? $user->profile->city,
                'occupation' => $validated['occupation'] ?? $user->profile->occupation,
                'bio' => $validated['bio'] ?? $user->profile->bio,
            ]);
        }

        $admin = Auth::guard('admin_web')->user();
        if ($admin) {
            $this->auditService->log($admin, 'web_admin_update_user', 'User', $user->id, $validated, $request);
        }

        return back()->with('success', "User #{$user->id} profile updated successfully.");
    }

    /**
     * Adjust User Wallet Coins
     */
    public function adjustWallet(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => 'required|integer',
            'reason' => 'required|string|min:5|max:255',
        ]);

        $amount = (int) $validated['amount'];
        $wallet = $user->wallet ?? Wallet::create(['user_id' => $user->id, 'balance' => 0]);

        if ($amount < 0 && ($wallet->balance + $amount) < 0) {
            return back()->with('error', "Cannot deduct {$amount} coins. Current balance is {$wallet->balance}.");
        }

        DB::transaction(function () use ($wallet, $user, $amount, $validated, $request) {
            $balanceBefore = $wallet->balance;
            $balanceAfter = $balanceBefore + $amount;
            $wallet->balance = $balanceAfter;
            $wallet->save();

            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $user->id,
                'type' => 'admin_adjustment',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => "Admin adjustment: {$validated['reason']}",
                'reference_id' => 'ADMIN_MANUAL_' . time(),
            ]);

            $admin = Auth::guard('admin_web')->user();
            if ($admin) {
                $this->auditService->log($admin, 'web_admin_wallet_adjust', 'User', $user->id, [
                    'amount' => $amount,
                    'reason' => $validated['reason'],
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                ], $request);
            }
        });

        return back()->with('success', "Adjusted {$amount} coins for User #{$user->id}.");
    }

    /**
     * Update User Status (Active, Suspended, Banned)
     */
    public function updateStatus(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:active,suspended,banned,deleted',
            'reason' => 'nullable|string|max:255',
        ]);

        $user->update(['status' => $validated['status']]);

        if (in_array($validated['status'], ['banned', 'suspended', 'deleted'])) {
            $user->tokens()->delete();
        }

        $admin = Auth::guard('admin_web')->user();
        if ($admin) {
            $this->auditService->log($admin, 'web_admin_status_change', 'User', $user->id, $validated, $request);
        }

        return back()->with('success', "User #{$user->id} status updated to {$validated['status']}.");
    }

    /**
     * Delete/Anonymize User
     */
    public function deleteUser(Request $request, User $user): RedirectResponse
    {
        $userId = $user->id;
        $user->tokens()->delete();
        $user->devices()->delete();

        $user->update([
            'status' => 'deleted',
            'name' => 'Deleted User',
            'email' => 'deleted_' . $userId . '_' . time() . '@anonymized.local',
        ]);

        if ($user->profile) {
            $user->profile->update([
                'name' => 'Deleted User',
                'bio' => null,
                'profile_visibility' => 'hidden',
            ]);
        }

        $admin = Auth::guard('admin_web')->user();
        if ($admin) {
            $this->auditService->log($admin, 'web_admin_delete_user', 'User', $userId, [], $request);
        }

        return redirect()->route('admin.users')->with('success', "User #{$userId} has been deleted.");
    }

    /**
     * Financial Transactions Ledger
     */
    public function wallets(Request $request): View
    {
        $query = WalletTransaction::with('user');

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.wallets.index', compact('transactions'));
    }

    /**
     * Call Records
     */
    public function calls(Request $request): View
    {
        $calls = Call::with(['caller', 'receiver'])->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.calls.index', compact('calls'));
    }

    /**
     * Abuse & Safety Reports
     */
    public function reports(Request $request): View
    {
        $query = Report::with(['reporter', 'reported']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.reports.index', compact('reports'));
    }

    /**
     * Review Report
     */
    public function reviewReport(Request $request, Report $report): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewing,resolved,dismissed',
            'resolution_notes' => 'nullable|string|max:500',
        ]);

        $report->update($validated);

        return back()->with('success', "Report #{$report->id} updated.");
    }

    /**
     * In-App Coin Packages Store
     */
    public function packages(): View
    {
        $packages = CoinPackage::orderBy('sort_order', 'asc')->get();
        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Store Coin Package
     */
    public function storePackage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:64',
            'coins' => 'required|integer|min:1',
            'bonus_coins' => 'nullable|integer|min:0',
            'price' => 'required|numeric|min:0',
            'google_product_id' => 'required|string|max:128|unique:coin_packages,google_product_id',
            'is_active' => 'boolean',
        ]);

        $validated['bonus_coins'] = $validated['bonus_coins'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active', true);

        CoinPackage::create($validated);

        return back()->with('success', 'Coin package created successfully.');
    }

    /**
     * Delete Coin Package
     */
    public function deletePackage(CoinPackage $package): RedirectResponse
    {
        $package->delete();
        return back()->with('success', 'Coin package deleted.');
    }

    /**
     * Virtual Gift Catalog
     */
    public function gifts(): View
    {
        $gifts = GiftCatalog::orderBy('sort_order', 'asc')->get();
        return view('admin.gifts.index', compact('gifts'));
    }

    /**
     * Store Virtual Gift
     */
    public function storeGift(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:64',
            'icon_url' => 'required|string',
            'coin_price' => 'required|integer|min:1',
            'category' => 'nullable|string|max:32',
        ]);

        GiftCatalog::create($validated);

        return back()->with('success', 'Virtual gift added successfully.');
    }

    /**
     * Delete Virtual Gift
     */
    public function deleteGift(GiftCatalog $gift): RedirectResponse
    {
        $gift->delete();
        return back()->with('success', 'Gift deleted.');
    }

    /**
     * VIP Subscriptions
     */
    public function subscriptions(): View
    {
        $subscriptions = Subscription::with(['user', 'plan'])->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * System Settings
     */
    public function settings(): View
    {
        $settings = AdminSetting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Save System Settings
     */
    public function saveSettings(Request $request): RedirectResponse
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            AdminSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return back()->with('success', 'System settings saved successfully.');
    }
}
