@extends('admin.layouts.app')

@section('title', 'Dashboard Overview')
@section('page_title', 'Dashboard Overview')

@section('content')
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Total Users</span>
                <div class="stat-icon" style="background: var(--primary-light); color: var(--primary);"><i class="fa-solid fa-users"></i></div>
            </div>
            <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-sub"><span style="font-weight: bold;">+{{ $stats['new_users_today'] }}</span> joined today</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Mutual Matches</span>
                <div class="stat-icon" style="background: var(--danger-light); color: var(--danger);"><i class="fa-solid fa-heart"></i></div>
            </div>
            <div class="stat-value">{{ number_format($stats['total_matches']) }}</div>
            <div class="stat-sub">Active connections</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Completed Calls</span>
                <div class="stat-icon" style="background: var(--success-light); color: var(--success);"><i class="fa-solid fa-phone"></i></div>
            </div>
            <div class="stat-value">{{ number_format($stats['total_calls']) }}</div>
            <div class="stat-sub"><span style="font-weight: bold;">{{ number_format($stats['call_minutes']) }}</span> minutes billed</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">VIP Subscriptions</span>
                <div class="stat-icon" style="background: var(--warning-light); color: var(--warning);"><i class="fa-solid fa-gem"></i></div>
            </div>
            <div class="stat-value">{{ number_format($stats['active_subs']) }}</div>
            <div class="stat-sub">Active members</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Coin Sales Revenue</span>
                <div class="stat-icon" style="background: var(--success-light); color: var(--success);"><i class="fa-solid fa-dollar-sign"></i></div>
            </div>
            <div class="stat-value">${{ number_format($stats['coin_sales_usd'], 2) }}</div>
            <div class="stat-sub">{{ number_format($stats['circulating_coins']) }} coins in wallets</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Pending Reports</span>
                <div class="stat-icon" style="background: var(--danger-light); color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i></div>
            </div>
            <div class="stat-value">{{ number_format($stats['pending_reports']) }}</div>
            <div class="stat-sub">Requires Moderation</div>
        </div>
    </div>

    <!-- Recent Users Table -->
    <div class="card">
        <div class="card-header">
            <span class="card-title">Recent Registrations</span>
            <a href="{{ route('admin.users') }}" class="btn btn-secondary btn-sm">View All Users →</a>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Wallet Balance</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['recent_users'] as $user)
                        <tr>
                            <td>
                                <strong>{{ $user->name ?? ($user->profile?->name ?? explode('@', $user->email)[0]) }}</strong>
                                <br><span style="font-size: 11px; color: var(--text-muted);">ID: #{{ $user->id }}</span>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge badge-{{ $user->status }}">{{ ucfirst($user->status) }}</span></td>
                            <td><strong>{{ $user->wallet?->balance ?? 0 }}</strong> 🪙</td>
                            <td style="font-size: 12px; color: var(--text-secondary);">{{ $user->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-eye"></i> Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 25px;">No users registered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- System Diagnostics -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fa-solid fa-server" style="color: var(--primary); margin-right: 6px;"></i> System Engine & Infrastructure</span>
        </div>
        <div style="padding: 20px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                <div>
                    <span style="font-size: 12px; color: var(--text-secondary);">Backend Framework</span>
                    <div style="font-weight: 600; margin-top: 4px; color: var(--success);"><i class="fa-solid fa-check-circle"></i> Laravel 12 (PHP 8.4)</div>
                </div>
                <div>
                    <span style="font-size: 12px; color: var(--text-secondary);">Database Engine</span>
                    <div style="font-weight: 600; margin-top: 4px; color: var(--success);"><i class="fa-solid fa-check-circle"></i> MySQL 8 ACID Ledger</div>
                </div>
                <div>
                    <span style="font-size: 12px; color: var(--text-secondary);">REST API Engine</span>
                    <div style="font-weight: 600; margin-top: 4px;"><code>/api/v1/</code> Active</div>
                </div>
                <div>
                    <span style="font-size: 12px; color: var(--text-secondary);">WebSockets Server</span>
                    <div style="font-weight: 600; margin-top: 4px;">Laravel Reverb (Port 8080)</div>
                </div>
            </div>
        </div>
    </div>
@endsection
