@extends('admin.layouts.app')

@section('title', 'Conversation Transcript #' . $conversation->id)
@section('page_title', 'Conversation Transcript #' . $conversation->id)

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.conversations') }}" class="btn btn-secondary" style="font-size: 12.5px; padding: 6px 14px;">
        <i class="fa-solid fa-arrow-left"></i> Back to All Conversations
    </a>
</div>

<div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">
    <!-- Chat Transcript Card -->
    <div class="card" style="display: flex; flex-direction: column; min-height: 600px;">
        <div class="card-header">
            <span class="card-title"><i class="fa-solid fa-comments"></i> Live Chat Transcript</span>
            <span style="font-size: 12px; color: var(--text-muted);">{{ $conversation->messages->count() }} messages recorded</span>
        </div>

        <!-- Messages Stream -->
        <div style="flex: 1; padding: 20px; overflow-y: auto; background: var(--bg-primary); display: flex; flex-direction: column; gap: 14px; max-height: 500px;">
            @forelse($conversation->messages as $msg)
                @php
                    $sender = $msg->sender;
                    $isBot = $sender?->is_bot ?? false;
                @endphp
                <div style="display: flex; flex-direction: column; align-items: {{ $isBot ? 'flex-start' : 'flex-end' }};">
                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 4px;">
                        <span style="font-size: 11px; font-weight: 600; color: var(--text-secondary);">
                            {{ $sender?->name ?? 'User #'.$msg->sender_id }}
                            @if($isBot)
                                <span class="badge" style="background: #fce7f3; color: #db2777; font-size: 8.5px; padding: 1px 4px;">BOT</span>
                            @endif
                        </span>
                        <span style="font-size: 10px; color: var(--text-muted);">
                            {{ \Carbon\Carbon::parse($msg->created_at)->format('M d, g:i A') }}
                        </span>
                    </div>

                    <div style="max-width: 75%; padding: 10px 14px; border-radius: 16px; font-size: 13.5px; line-height: 1.4;
                        {{ $isBot ? 'background: var(--bg-surface); border: 1px solid var(--border-color); color: var(--text-primary); border-bottom-left-radius: 4px;' : 'background: var(--primary); color: white; border-bottom-right-radius: 4px;' }}">
                        {{ $msg->body }}
                    </div>
                </div>
            @empty
                <div style="text-align: center; color: var(--text-muted); padding: 40px;">
                    <i class="fa-solid fa-comment-slash" style="font-size: 28px; margin-bottom: 8px; display: block;"></i>
                    No messages sent in this conversation yet.
                </div>
            @endforelse
        </div>

        <!-- Send Admin Message Form -->
        <div style="padding: 16px 20px; border-top: 1px solid var(--border-color); background: var(--bg-surface);">
            <form action="{{ route('admin.conversations.message', $conversation->id) }}" method="POST">
                @csrf
                <div style="display: flex; gap: 10px; align-items: flex-end;">
                    <div style="width: 180px;">
                        <label class="form-label" style="font-size: 11px;">Send As:</label>
                        <select name="sender_id" class="form-control" style="padding: 8px 10px; font-size: 12.5px;" required>
                            @foreach($conversation->participants as $part)
                                @php $u = $part->user; @endphp
                                <option value="{{ $u?->id }}" {{ $u?->is_bot ? 'selected' : '' }}>
                                    {{ $u?->name }} ({{ $u?->is_bot ? 'Bot' : 'User' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="flex: 1;">
                        <input type="text" name="body" class="form-control" placeholder="Type a reply to send into this chat..." style="padding: 9px 12px; font-size: 13px;" required autocomplete="off">
                    </div>

                    <button type="submit" class="btn btn-primary" style="padding: 9px 16px;">
                        <i class="fa-solid fa-paper-plane"></i> Send
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Participants Info Card -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fa-solid fa-users"></i> Participants Info</span>
        </div>
        <div style="padding: 16px; display: flex; flex-direction: column; gap: 16px;">
            @foreach($conversation->participants as $part)
                @php
                    $u = $part->user;
                    $photo = $u?->photos->where('is_primary', true)->first()?->url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100';
                @endphp
                <div style="display: flex; gap: 12px; align-items: center; padding: 12px; border-radius: 10px; background: var(--bg-primary); border: 1px solid var(--border-color);">
                    <img src="{{ $photo }}" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border-color);">
                    <div style="flex: 1;">
                        <div style="font-size: 14px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 6px;">
                            {{ $u?->name ?? 'User #'.$part->user_id }}
                            @if($u?->is_bot)
                                <span class="badge" style="background: #fce7f3; color: #db2777; font-size: 9px;">BOT</span>
                            @endif
                        </div>
                        <div style="font-size: 11.5px; color: var(--text-muted);">
                            {{ $u?->email ?? $u?->phone ?? 'Registered User' }}
                        </div>
                        <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">
                            Age: {{ $u?->profile?->age ?? '24' }} • {{ $u?->profile?->city ?? 'Mumbai' }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
