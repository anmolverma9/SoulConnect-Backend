@extends('admin.layouts.app')

@section('title', 'Call Records')
@section('page_title', 'Voice & Video Call Records')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title">Call History & Billing Logs (Total: {{ $calls->total() }})</span>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Call ID</th>
                        <th>Caller</th>
                        <th>Receiver</th>
                        <th>Type</th>
                        <th>Duration</th>
                        <th>Coins Charged</th>
                        <th>Status</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($calls as $c)
                        @php
                            $mins = ceil(($c->duration_seconds ?? 0) / 60);
                        @endphp
                        <tr>
                            <td><code>#{{ $c->id }}</code></td>
                            <td>
                                <a href="{{ route('admin.users.show', $c->caller_id) }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">
                                    {{ $c->caller?->name ?? 'User #' . $c->caller_id }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.users.show', $c->receiver_id) }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">
                                    {{ $c->receiver?->name ?? 'User #' . $c->receiver_id }}
                                </a>
                            </td>
                            <td>
                                <span class="badge {{ $c->type == 'video' ? 'badge-active' : 'badge-pending' }}">
                                    <i class="fa-solid {{ $c->type == 'video' ? 'fa-video' : 'fa-phone' }}" style="margin-right: 4px;"></i> {{ ucfirst($c->type) }}
                                </span>
                            </td>
                            <td>{{ $c->duration_seconds ?? 0 }}s ({{ $mins }}m)</td>
                            <td><strong>{{ $c->total_cost ?? 0 }}</strong> 🪙</td>
                            <td><span class="badge badge-{{ $c->status == 'ended' ? 'resolved' : 'pending' }}">{{ ucfirst($c->status) }}</span></td>
                            <td style="font-size: 12px; color: var(--text-secondary);">{{ $c->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 25px;">No calls recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $calls->appends(request()->query())->links('admin.layouts.pagination') }}
    </div>
@endsection
