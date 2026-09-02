@extends('admin.layouts.app')

@section('title', 'Financial Ledger')
@section('page_title', 'Financial Transactions & Audit Ledger')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title">Live Coin Transactions Ledger (Total: {{ $transactions->total() }})</span>
            <form action="{{ route('admin.wallets') }}" method="GET" style="display: flex; gap: 10px;">
                <div class="input-group">
                    <select name="type" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        <option value="coin_purchase" {{ request('type') == 'coin_purchase' ? 'selected' : '' }}>Coin Purchases</option>
                        <option value="call_charge" {{ request('type') == 'call_charge' ? 'selected' : '' }}>Call Charges</option>
                        <option value="gift_sent" {{ request('type') == 'gift_sent' ? 'selected' : '' }}>Gifts Sent</option>
                        <option value="boost_purchase" {{ request('type') == 'boost_purchase' ? 'selected' : '' }}>Boosts</option>
                        <option value="super_like_charge" {{ request('type') == 'super_like_charge' ? 'selected' : '' }}>Super Likes</option>
                        <option value="admin_adjustment" {{ request('type') == 'admin_adjustment' ? 'selected' : '' }}>Admin Adjustments</option>
                    </select>
                </div>
                @if(request('type'))
                    <a href="{{ route('admin.wallets') }}" class="btn btn-secondary btn-sm">Reset</a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tx ID</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Balance (Before ➔ After)</th>
                        <th>Description</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                        @php
                            $isCredit = $t->amount > 0;
                            $userDisplayName = $t->user?->name ?? ($t->user?->profile?->name ?? 'User #' . $t->user_id);
                        @endphp
                        <tr>
                            <td><code>#{{ $t->id }}</code></td>
                            <td>
                                <a href="{{ route('admin.users.show', $t->user_id) }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">
                                    {{ $userDisplayName }}
                                </a>
                                <br><span style="font-size: 11px; color: var(--text-muted);">#{{ $t->user_id }}</span>
                            </td>
                            <td><span class="badge badge-active">{{ ucfirst(str_replace('_', ' ', $t->type)) }}</span></td>
                            <td>
                                @if($isCredit)
                                    <span style="color: var(--success); font-weight: bold;">+{{ $t->amount }} 🪙</span>
                                @else
                                    <span style="color: var(--danger); font-weight: bold;">{{ $t->amount }} 🪙</span>
                                @endif
                            </td>
                            <td>{{ $t->balance_before }} ➔ <strong>{{ $t->balance_after }}</strong></td>
                            <td style="font-size: 13px;">{{ $t->description ?: '-' }}</td>
                            <td style="font-size: 12px; color: var(--text-secondary);">{{ $t->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 25px;">No transactions recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $transactions->appends(request()->query())->links('admin.layouts.pagination') }}
    </div>
@endsection
