@extends('admin.layouts.app')

@section('title', 'Live Chat Monitor')
@section('page_title', 'Live Conversations & Chat Monitor')

@section('content')
<div style="margin-bottom: 24px;">
    <p style="color: var(--text-secondary); font-size: 13.5px;">Monitor active user chats, inspect message histories, and engage directly with users on behalf of any bot profile.</p>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fa-solid fa-comments"></i> Active Conversations ({{ $conversations->total() }})</span>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width: 70px;">ID</th>
                    <th>Participants</th>
                    <th>Latest Message</th>
                    <th style="width: 130px;">Last Activity</th>
                    <th style="width: 140px; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($conversations as $conv)
                <tr>
                    <td style="font-weight: 600; color: var(--text-muted);">#{{ $conv->id }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            @foreach($conv->participants as $part)
                                @php
                                    $u = $part->user;
                                    $photo = $u?->photos->where('is_primary', true)->first()?->url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100';
                                @endphp
                                <div style="display: flex; align-items: center; gap: 6px; background: var(--bg-primary); padding: 4px 8px; border-radius: 8px; border: 1px solid var(--border-color);">
                                    <img src="{{ $photo }}" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;">
                                    <span style="font-size: 12.5px; font-weight: 600;">{{ $u?->name ?? 'User #'.$part->user_id }}</span>
                                    @if($u?->is_bot)
                                        <span class="badge" style="background: #fce7f3; color: #db2777; font-size: 9px; padding: 2px 4px;">BOT</span>
                                    @endif
                                </div>
                                @if(!$loop->last)
                                    <span style="color: var(--text-muted); font-size: 12px;"><i class="fa-solid fa-arrow-right-arrow-left"></i></span>
                                @endif
                            @endforeach
                        </div>
                    </td>
                    <td style="font-size: 13px; color: var(--text-secondary); max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        @if($conv->lastMessage)
                            <strong style="color: var(--text-primary);">{{ $conv->lastMessage->sender?->name }}:</strong> "{{ $conv->lastMessage->body }}"
                        @else
                            <em style="color: var(--text-muted);">No messages yet</em>
                        @endif
                    </td>
                    <td style="font-size: 12px; color: var(--text-secondary);">
                        {{ $conv->last_message_at ? \Carbon\Carbon::parse($conv->last_message_at)->diffForHumans() : 'N/A' }}
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.conversations.show', $conv->id) }}" class="btn btn-primary" style="padding: 5px 12px; font-size: 12px;">
                            <i class="fa-solid fa-eye"></i> View Chat
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 32px; color: var(--text-muted);">
                        <i class="fa-solid fa-comments" style="font-size: 28px; margin-bottom: 8px; display: block;"></i>
                        No conversations found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($conversations->hasPages())
    <div class="pagination">
        <div>Showing {{ $conversations->firstItem() }} to {{ $conversations->lastItem() }} of {{ $conversations->total() }} conversations</div>
        <div>{{ $conversations->links() }}</div>
    </div>
    @endif
</div>
@endsection
