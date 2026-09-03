@extends('admin.layouts.app')

@php
    $displayName = $user->name ?? ($user->profile?->name ?? explode('@', $user->email)[0]);
@endphp

@section('title', "User Dossier: {$displayName}")
@section('page_title', "User Dossier: {$displayName}")

@section('content')
    <div style="display: flex; gap: 24px; flex-wrap: wrap; margin-bottom: 24px;">
        <!-- Left: Profile Summary Card -->
        <div class="card" style="flex: 1; min-width: 320px;">
            <div class="card-header">
                <span class="card-title">Profile Overview</span>
                <span class="badge badge-{{ $user->status }}">{{ strtoupper($user->status) }}</span>
            </div>
            <div style="padding: 24px;">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                    <div style="width: 64px; height: 64px; border-radius: 50%; background: linear-gradient(135deg, #ec4899, #8b5cf6); color: white; display: flex; align-items: center; justify-content: center; font-size: 26px; font-weight: bold;">
                        {{ strtoupper(substr($displayName, 0, 1)) }}
                    </div>
                    <div>
                        <h2 style="font-size: 20px; font-weight: 700;">{{ $displayName }}</h2>
                        <div style="font-size: 13px; color: var(--text-secondary);">{{ $user->email }} • User #{{ $user->id }}</div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
                    <div style="background: var(--bg-primary); padding: 12px; border-radius: 8px;">
                        <span style="font-size: 11px; color: var(--text-muted); text-transform: uppercase;">Coins Balance</span>
                        <div style="font-size: 20px; font-weight: bold; margin-top: 2px;">{{ $user->wallet?->balance ?? 0 }} 🪙</div>
                    </div>
                    <div style="background: var(--bg-primary); padding: 12px; border-radius: 8px;">
                        <span style="font-size: 11px; color: var(--text-muted); text-transform: uppercase;">VIP Membership</span>
                        <div style="font-size: 15px; font-weight: 600; margin-top: 2px;">
                            {{ $user->activeSubscription?->plan->name ?? 'Free Tier' }}
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">Bio</label>
                    <div style="font-size: 13.5px; background: var(--bg-primary); padding: 12px; border-radius: 8px; margin-top: 4px; line-height: 20px;">
                        {{ $user->profile?->bio ?: 'No bio provided yet.' }}
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                    <div>
                        <label style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">Location</label>
                        <div style="font-size: 13.5px; margin-top: 2px;">{{ $user->profile?->city ?: 'Unknown' }}, {{ $user->profile?->country ?: '' }}</div>
                    </div>
                    <div>
                        <label style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">Occupation</label>
                        <div style="font-size: 13.5px; margin-top: 2px;">{{ $user->profile?->occupation ?: 'Not specified' }}</div>
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">Registered Devices</label>
                    <div style="margin-top: 6px; display: flex; gap: 8px; flex-wrap: wrap;">
                        @forelse($user->devices as $device)
                            <span class="badge badge-active">{{ ucfirst($device->platform) }}: {{ $device->device_name ?? 'Device' }}</span>
                        @empty
                            <span style="font-size: 13px; color: var(--text-muted);">No devices registered</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Edit Profile Form -->
        <div class="card" style="flex: 1.2; min-width: 320px;">
            <div class="card-header">
                <span class="card-title">Edit Account & Permissions</span>
            </div>
            <div style="padding: 24px;">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group">
                            <label>Full Display Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $displayName) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender" class="form-control">
                                <option value="male" {{ ($user->profile?->gender == 'male') ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ ($user->profile?->gender == 'female') ? 'selected' : '' }}>Female</option>
                                <option value="non_binary" {{ ($user->profile?->gender == 'non_binary') ? 'selected' : '' }}>Non-Binary</option>
                                <option value="other" {{ ($user->profile?->gender == 'other') ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Account Status</label>
                            <select name="status" class="form-control">
                                <option value="active" {{ ($user->status == 'active') ? 'selected' : '' }}>Active</option>
                                <option value="suspended" {{ ($user->status == 'suspended') ? 'selected' : '' }}>Suspended</option>
                                <option value="banned" {{ ($user->status == 'banned') ? 'selected' : '' }}>Banned</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $user->profile?->city) }}">
                        </div>
                        <div class="form-group">
                            <label>Occupation</label>
                            <input type="text" name="occupation" class="form-control" value="{{ old('occupation', $user->profile?->occupation) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Bio</label>
                        <textarea name="bio" class="form-control" rows="3">{{ old('bio', $user->profile?->bio) }}</textarea>
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
                        <a href="{{ route('admin.users') }}" class="btn btn-secondary">Back to Users</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SpacePay Payment Orders History -->
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header">
            <span class="card-title">
                <i class="fa-solid fa-credit-card" style="color: var(--primary);"></i> SpacePay Payment Orders ({{ count($paymentOrders) }})
            </span>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Gateway</th>
                        <th>Amount</th>
                        <th>Coins Credited</th>
                        <th>Bank / Txn ID</th>
                        <th>Status</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paymentOrders as $order)
                        <tr>
                            <td><code>{{ $order->order_id }}</code></td>
                            <td><span style="font-weight: 600; text-transform: uppercase;">{{ $order->gateway }}</span></td>
                            <td><strong>₹{{ number_format($order->amount, 2) }}</strong></td>
                            <td><strong style="color: var(--warning);">+{{ number_format($order->coins_to_credit) }}</strong> 🪙</td>
                            <td><code>{{ $order->bank_txn_id ?: '-' }}</code></td>
                            <td>
                                <span class="badge {{ $order->status === 'success' ? 'badge-active' : ($order->status === 'pending' ? 'badge-suspended' : 'badge-banned') }}">
                                    {{ strtoupper($order->status) }}
                                </span>
                            </td>
                            <td style="font-size: 12.5px; color: var(--text-secondary);">
                                {{ $order->created_at->format('d M Y, h:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 20px;">No SpacePay payment orders recorded for this user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Full Coin Ledger Transactions -->
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <i class="fa-solid fa-coins" style="color: var(--warning);"></i> Coin Ledger & Transactions ({{ count($transactions) }})
            </span>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Coins Amount</th>
                        <th>Balance Before</th>
                        <th>Balance After</th>
                        <th>Description / Reference</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $txn)
                        @php
                            $isCredit = in_array($txn->type, ['purchase', 'bonus', 'admin_credit', 'refund']) || $txn->amount > 0;
                        @endphp
                        <tr>
                            <td>#{{ $txn->id }}</td>
                            <td>
                                <span class="badge {{ $isCredit ? 'badge-active' : 'badge-suspended' }}">
                                    {{ ucfirst(str_replace('_', ' ', $txn->type)) }}
                                </span>
                            </td>
                            <td>
                                <strong style="color: {{ $isCredit ? '#10B981' : '#EF4444' }}; font-size: 14px;">
                                    {{ $isCredit ? '+' : '-' }}{{ abs($txn->amount) }} 🪙
                                </strong>
                            </td>
                            <td>{{ number_format($txn->balance_before) }}</td>
                            <td><strong>{{ number_format($txn->balance_after) }}</strong></td>
                            <td>{{ $txn->description ?: '-' }}</td>
                            <td style="font-size: 12.5px; color: var(--text-secondary);">
                                {{ $txn->created_at->format('d M Y, h:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 20px;">No coin transactions recorded for this user yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
