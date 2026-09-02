@extends('admin.layouts.app')

@section('title', 'Users & Profiles')
@section('page_title', 'Users & Profiles')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title">User Accounts & Balances (Total: {{ $users->total() }})</span>
            <form action="{{ route('admin.users') }}" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <div class="input-group">
                    <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted);"></i>
                    <input type="text" name="search" placeholder="Search name or email..." value="{{ request('search') }}">
                </div>
                <div class="input-group">
                    <select name="status" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.users') }}" class="btn btn-secondary btn-sm">Reset</a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Location & Role</th>
                        <th>Status</th>
                        <th>Wallet Balance</th>
                        <th>VIP Tier</th>
                        <th>Joined Date</th>
                        <th>Actions</th>
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
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $displayName }}</strong>
                                @if($gender)
                                    <span style="font-size: 11px; color: var(--text-muted);">({{ $gender }})</span>
                                @endif
                                <br><span style="font-size: 11px; color: var(--text-muted);">ID: #{{ $u->id }}</span>
                            </td>
                            <td>{{ $u->email }}</td>
                            <td>
                                <div style="font-weight: 500; font-size: 13px;">{{ $city ?: 'Not set' }}{{ $country ? ', ' . $country : '' }}</div>
                                <div style="font-size: 12px; color: var(--text-secondary);">{{ $occupation ?: 'No occupation' }}</div>
                            </td>
                            <td><span class="badge badge-{{ $u->status }}">{{ ucfirst($u->status) }}</span></td>
                            <td><strong>{{ $u->wallet?->balance ?? 0 }}</strong> <i class="fa-solid fa-coins" style="color: var(--warning); font-size: 12px;"></i></td>
                            <td>
                                @if($u->activeSubscription)
                                    <span class="badge badge-active">{{ $u->activeSubscription->plan->name ?? 'VIP Gold' }}</span>
                                @else
                                    <span style="color: var(--text-muted);">Free Tier</span>
                                @endif
                            </td>
                            <td style="font-size: 12px; color: var(--text-secondary);">{{ $u->created_at->format('d M Y') }}</td>
                            <td style="white-space: nowrap;">
                                <a href="{{ route('admin.users.show', $u->id) }}" class="btn btn-secondary btn-sm" title="View Full Dossier">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="openWalletModal({{ $u->id }}, '{{ addslashes($displayName) }}', {{ $u->wallet?->balance ?? 0 }})" title="Adjust Coins">
                                    <i class="fa-solid fa-coins"></i>
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
                            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 25px;">No users found matching your criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="pagination">
                <span style="font-size: 13px; color: var(--text-secondary);">Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users</span>
                <div>
                    @if($users->onFirstPage())
                        <button class="btn btn-secondary btn-sm" disabled>Previous</button>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" class="btn btn-secondary btn-sm">Previous</a>
                    @endif

                    @if($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="btn btn-secondary btn-sm">Next</a>
                    @else
                        <button class="btn btn-secondary btn-sm" disabled>Next</button>
                    @endif
                </div>
            </div>
        @endif
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
                            <option value="banned">Banned (Permanent block)</option>
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
