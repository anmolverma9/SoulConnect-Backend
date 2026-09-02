@extends('admin.layouts.app')

@section('title', 'Subscriptions')
@section('page_title', 'Active VIP Memberships')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title">VIP Subscriptions (Total: {{ $subscriptions->total() }})</span>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Subscription ID</th>
                        <th>User</th>
                        <th>Plan</th>
                        <th>Order Reference</th>
                        <th>Starts At</th>
                        <th>Ends At</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $s)
                        <tr>
                            <td><code>#{{ $s->id }}</code></td>
                            <td>
                                <a href="{{ route('admin.users.show', $s->user_id) }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">
                                    {{ $s->user?->name ?? 'User #' . $s->user_id }}
                                </a>
                            </td>
                            <td><span class="badge badge-active">{{ $s->plan?->name ?? 'VIP Gold' }}</span></td>
                            <td><code>{{ $s->order_id ?: 'Direct / Free' }}</code></td>
                            <td style="font-size: 12px; color: var(--text-secondary);">{{ $s->starts_at ? $s->starts_at->format('d M Y') : '-' }}</td>
                            <td style="font-size: 12px; color: var(--text-secondary);">{{ $s->ends_at ? $s->ends_at->format('d M Y') : '-' }}</td>
                            <td><span class="badge badge-{{ $s->status }}">{{ ucfirst($s->status) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 25px;">No active subscriptions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $subscriptions->appends(request()->query())->links('admin.layouts.pagination') }}
    </div>
@endsection
