@extends('admin.layouts.app')

@section('title', 'Users & Profiles Management')
@section('page_title', 'Users & Profiles Management')

@section('content')
    <!-- Top Summary Metric Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px;">
        <a href="{{ route('admin.users', ['user_type' => 'real']) }}" style="text-decoration: none;">
            <div class="card" style="padding: 16px 20px; display: flex; align-items: center; gap: 16px; border-left: 4px solid var(--primary); transition: transform 0.2s; cursor: pointer;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <div>
                    <div style="font-size: 11.5px; font-weight: 600; text-transform: uppercase; color: var(--text-muted);">Real Registered Users</div>
                    <div style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin-top: 2px;">{{ number_format($metrics['total_real']) }}</div>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.users', ['user_type' => 'bots']) }}" style="text-decoration: none;">
            <div class="card" style="padding: 16px 20px; display: flex; align-items: center; gap: 16px; border-left: 4px solid #8B5CF6; transition: transform 0.2s; cursor: pointer;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(139, 92, 246, 0.12); color: #8B5CF6; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div>
                    <div style="font-size: 11.5px; font-weight: 600; text-transform: uppercase; color: var(--text-muted);">AI Simulated Bots</div>
                    <div style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin-top: 2px;">{{ number_format($metrics['total_bots']) }}</div>
                </div>
            </div>
        </a>

        <div class="card" style="padding: 16px 20px; display: flex; align-items: center; gap: 16px; border-left: 4px solid var(--warning);">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: var(--warning-light); color: var(--warning); display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fa-solid fa-crown"></i>
            </div>
            <div>
                <div style="font-size: 11.5px; font-weight: 600; text-transform: uppercase; color: var(--text-muted);">VIP Subscribers</div>
                <div style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin-top: 2px;">{{ number_format($metrics['total_vip']) }}</div>
            </div>
        </div>

        <div class="card" style="padding: 16px 20px; display: flex; align-items: center; gap: 16px; border-left: 4px solid #10B981;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16, 185, 129, 0.12); color: #10B981; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div>
                <div style="font-size: 11.5px; font-weight: 600; text-transform: uppercase; color: var(--text-muted);">Coins In Wallets</div>
                <div style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin-top: 2px;">{{ number_format($metrics['circulating_coins']) }} 🪙</div>
            </div>
        </div>
    </div>

    <!-- Main Filter & Control Center -->
    <div class="card" style="margin-bottom: 24px;">
        <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-color);">
            <!-- User Type Segmented Switcher -->
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
                <div style="display: inline-flex; background: var(--bg-primary); padding: 4px; border-radius: 12px; border: 1px solid var(--border-color);">
                    <a href="{{ route('admin.users', array_merge(request()->query(), ['user_type' => 'real'])) }}" 
                       style="padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 6px; transition: all 0.2s; {{ $userType === 'real' ? 'background: var(--primary); color: white; box-shadow: 0 2px 6px rgba(236,72,153,0.3);' : 'color: var(--text-secondary);' }}">
                        <i class="fa-solid fa-user"></i> Real Users ({{ $metrics['total_real'] }})
                    </a>

                    <a href="{{ route('admin.users', array_merge(request()->query(), ['user_type' => 'bots'])) }}" 
                       style="padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 6px; transition: all 0.2s; {{ $userType === 'bots' ? 'background: #8B5CF6; color: white; box-shadow: 0 2px 6px rgba(139,92,246,0.3);' : 'color: var(--text-secondary);' }}">
                        <i class="fa-solid fa-robot"></i> AI Bots Only ({{ $metrics['total_bots'] }})
                    </a>

                    <a href="{{ route('admin.users', array_merge(request()->query(), ['user_type' => 'all'])) }}" 
                       style="padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 6px; transition: all 0.2s; {{ $userType === 'all' ? 'background: var(--text-primary); color: white;' : 'color: var(--text-secondary);' }}">
                        <i class="fa-solid fa-users"></i> All ({{ $metrics['total_real'] + $metrics['total_bots'] }})
                    </a>
                </div>

                <div style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">
                    Showing <strong style="color: var(--text-primary);">{{ $users->total() }}</strong> accounts
                </div>
            </div>

            <!-- Advanced Filter Controls Form -->
            <form action="{{ route('admin.users') }}" method="GET" style="margin-top: 16px; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; align-items: center;">
                <input type="hidden" name="user_type" value="{{ $userType }}">

                <!-- Search Input -->
                <div class="input-group" style="grid-column: span 2; min-width: 260px;">
                    <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted);"></i>
                    <input type="text" name="search" placeholder="Search name, email, or city..." value="{{ request('search') }}">
                </div>

                <!-- Status Filter -->
                <div class="input-group">
                    <select name="status" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>🟢 Active</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>🟡 Suspended</option>
                        <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>🔴 Banned</option>
                    </select>
                </div>

                <!-- Gender Filter -->
                <div class="input-group">
                    <select name="gender" onchange="this.form.submit()">
                        <option value="">All Genders</option>
                        <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>👩 Female</option>
                        <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>👨 Male</option>
                        <option value="non_binary" {{ request('gender') === 'non_binary' ? 'selected' : '' }}>🧑 Non-Binary</option>
                    </select>
                </div>

                <!-- VIP Tier Filter -->
                <div class="input-group">
                    <select name="vip" onchange="this.form.submit()">
                        <option value="">All Memberships</option>
                        <option value="vip" {{ request('vip') === 'vip' ? 'selected' : '' }}>⭐ VIP Subscribers</option>
                        <option value="free" {{ request('vip') === 'free' ? 'selected' : '' }}>Free Tier Only</option>
                    </select>
                </div>

                <!-- Coin Balance Filter -->
                <div class="input-group">
                    <select name="coins" onchange="this.form.submit()">
                        <option value="">All Coin Balances</option>
                        <option value="positive" {{ request('coins') === 'positive' ? 'selected' : '' }}>🪙 Has Coins (>0)</option>
                        <option value="high" {{ request('coins') === 'high' ? 'selected' : '' }}>💰 High Balance (500+)</option>
                        <option value="zero" {{ request('coins') === 'zero' ? 'selected' : '' }}>Zero Balance (0)</option>
                    </select>
                </div>

                <!-- Sort Order -->
                <div class="input-group">
                    <select name="sort" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>🕒 Newest First</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>⏳ Oldest First</option>
                        <option value="coins_desc" {{ request('sort') === 'coins_desc' ? 'selected' : '' }}>🪙 Most Coins</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>🔤 Name (A-Z)</option>
                    </select>
                </div>

                <!-- Submit / Reset Buttons -->
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn btn-primary" style="padding: 9px 16px;">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                    @if(request()->hasAny(['search', 'status', 'gender', 'vip', 'coins', 'sort']))
                        <a href="{{ route('admin.users', ['user_type' => $userType]) }}" class="btn btn-secondary" style="padding: 9px 14px;" title="Clear all filters">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>User Account</th>
                        <th>Email & Contact</th>
                        <th>Location & Role</th>
                        <th>Status</th>
                        <th>Coins Balance</th>
                        <th>VIP Tier</th>
                        <th>Registered</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        @php
                            $displayName = $u->name ?? ($u->profile?->name ?? explode('@', $u->email)[0]);
                            $city = $u->profile?->city;
                            $country = $u->profile?->country;
                            $occupation = $u->profile?->occupation;
                            $gender = $u->profile?->gender ? ucfirst($u->profile->gender) : null;
                            $avatarUrl = $u->primaryPhoto?->full_url;
                            $initial = strtoupper(substr($displayName, 0, 1));
                        @endphp
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    @if($avatarUrl)
                                        <img src="{{ $avatarUrl }}" alt="{{ $displayName }}" style="width: 42px; height: 42px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border-color);">
                                    @else
                                        <div style="width: 42px; height: 42px; border-radius: 50%; background: linear-gradient(135deg, #EC4899, #8B5CF6); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px;">
                                            {{ $initial }}
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight: 700; font-size: 14px; color: var(--text-primary); display: flex; align-items: center; gap: 6px;">
                                            {{ $displayName }}
                                            @if($u->is_bot)
                                                <span style="font-size: 10px; background: rgba(139, 92, 246, 0.15); color: #8B5CF6; padding: 2px 6px; border-radius: 6px; font-weight: 700;">🤖 BOT</span>
                                            @endif
                                        </div>
                                        <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 1px;">
                                            ID: #{{ $u->id }}
                                            @if($gender)
                                                • {{ $gender }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 13px; font-weight: 500;">{{ $u->email }}</div>
                                @if($u->phone)
                                    <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 1px;">{{ $u->phone }}</div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 500; font-size: 13px;">{{ $city ?: 'Not set' }}{{ $country ? ', ' . $country : '' }}</div>
                                <div style="font-size: 12px; color: var(--text-secondary);">{{ $occupation ?: 'No occupation' }}</div>
                            </td>
                            <td>
                                <span class="badge badge-{{ $u->status }}">{{ ucfirst($u->status) }}</span>
                            </td>
                            <td>
                                <strong style="font-size: 14px; color: var(--text-primary);">{{ number_format($u->wallet?->balance ?? 0) }}</strong> 
                                <i class="fa-solid fa-coins" style="color: var(--warning); font-size: 13px;"></i>
                            </td>
                            <td>
                                @if($u->activeSubscription)
                                    <span class="badge badge-active">
                                        <i class="fa-solid fa-gem" style="margin-right: 3px;"></i> {{ $u->activeSubscription->plan->name ?? 'VIP Gold' }}
                                    </span>
                                @else
                                    <span style="color: var(--text-muted); font-size: 12.5px;">Free Tier</span>
                                @endif
                            </td>
                            <td style="font-size: 12.5px; color: var(--text-secondary);">
                                {{ $u->created_at->format('d M Y') }}
                                <div style="font-size: 11px; color: var(--text-muted);">{{ $u->created_at->format('h:i A') }}</div>
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <a href="{{ route('admin.users.show', $u->id) }}" class="btn btn-secondary btn-sm" title="View Full Dossier">
                                    <i class="fa-solid fa-eye"></i> Details
                                </a>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="openWalletModal({{ $u->id }}, '{{ addslashes($displayName) }}', {{ $u->wallet?->balance ?? 0 }})" title="Adjust Coins">
                                    <i class="fa-solid fa-coins" style="color: var(--warning);"></i>
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="openStatusModal({{ $u->id }}, '{{ $u->status }}')" title="Change Status">
                                    <i class="fa-solid fa-user-gear"></i>
                                </button>
                                <form action="{{ route('admin.users.delete', $u->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete {{ addslashes($displayName) }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete Account"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 36px 20px;">
                                <div style="font-size: 36px; margin-bottom: 8px;">🔍</div>
                                <div style="font-size: 15px; font-weight: 600; color: var(--text-primary);">No users found</div>
                                <div style="font-size: 13px; color: var(--text-secondary); margin-top: 4px;">Try changing or resetting your active search filters.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding: 14px 20px;">
            {{ $users->appends(request()->query())->links('admin.layouts.pagination') }}
        </div>
    </div>
@endsection

@section('modals')
    <!-- MODAL: ADJUST WALLET -->
    <div id="walletModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h3 style="font-size: 15px; font-weight: 600;">Adjust User Coins</h3>
                <button onclick="closeModal('walletModal')" style="background: none; border: none; font-size: 18px; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>
            <form id="walletForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Target User</label>
                        <input type="text" id="adjustUserName" class="form-control" readonly style="opacity: 0.7;">
                    </div>
                    <div class="form-group">
                        <label>Coin Adjustment (+ to add, - to deduct)</label>
                        <input type="number" name="amount" class="form-control" placeholder="e.g. 100 or -50" required>
                    </div>
                    <div class="form-group">
                        <label>Audit Reason</label>
                        <input type="text" name="reason" class="form-control" placeholder="e.g. Customer support bonus" required minlength="5">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('walletModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Apply Adjustment</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: STATUS -->
    <div id="statusModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h3 style="font-size: 15px; font-weight: 600;">Update Account Status</h3>
                <button onclick="closeModal('statusModal')" style="background: none; border: none; font-size: 18px; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Account Status</label>
                        <select name="status" id="statusSelect" class="form-control">
                            <option value="active">Active (Full access)</option>
                            <option value="suspended">Suspended (Temporary hold)</option>
                            <option value="banned" style="color: var(--danger);">Banned (Permanent block)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Reason for Action</label>
                        <input type="text" name="reason" class="form-control" placeholder="e.g. Terms violation">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('statusModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Status</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openWalletModal(userId, name, currentBalance) {
            document.getElementById('adjustUserName').value = `${name} (#${userId}) — Current: ${currentBalance} 🪙`;
            document.getElementById('walletForm').action = `/admin/users/${userId}/wallet/adjust`;
            openModal('walletModal');
        }

        function openStatusModal(userId, currentStatus) {
            document.getElementById('statusSelect').value = currentStatus;
            document.getElementById('statusForm').action = `/admin/users/${userId}/status`;
            openModal('statusModal');
        }
    </script>
@endsection
