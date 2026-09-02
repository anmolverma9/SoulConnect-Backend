@extends('admin.layouts.app')

@section('title', 'Bot Messages Bank')
@section('page_title', 'Automated Bot Messages Bank')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        <p style="color: var(--text-secondary); font-size: 13.5px;">Manage automated greetings, openers, and follow-up templates sent by female profiles to new users.</p>
    </div>
    <button onclick="openModal('addMessageModal')" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add New Message Template
    </button>
</div>

<!-- Stats Row -->
<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-title">Active Message Templates</span>
            <div class="stat-icon" style="background: var(--primary-light); color: var(--primary);"><i class="fa-solid fa-message"></i></div>
        </div>
        <div class="stat-value">{{ $totalActive }}</div>
        <div class="stat-sub">Ready for automated dispatch</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-title">Active Bot Profiles</span>
            <div class="stat-icon" style="background: var(--success-light); color: var(--success);"><i class="fa-solid fa-robot"></i></div>
        </div>
        <div class="stat-value">{{ $totalBots }}</div>
        <div class="stat-sub">Female profiles in rotation</div>
    </div>
</div>

<!-- Category Filters -->
<div style="display: flex; gap: 8px; margin-bottom: 18px; flex-wrap: wrap;">
    <a href="{{ route('admin.bot_messages') }}" class="btn {{ empty($category) ? 'btn-primary' : 'btn-secondary' }}" style="font-size: 12px; padding: 6px 14px;">All Categories</a>
    <a href="{{ route('admin.bot_messages', ['category' => 'greeting']) }}" class="btn {{ $category === 'greeting' ? 'btn-primary' : 'btn-secondary' }}" style="font-size: 12px; padding: 6px 14px;">👋 Greetings</a>
    <a href="{{ route('admin.bot_messages', ['category' => 'flirty']) }}" class="btn {{ $category === 'flirty' ? 'btn-primary' : 'btn-secondary' }}" style="font-size: 12px; padding: 6px 14px;">💖 Flirty</a>
    <a href="{{ route('admin.bot_messages', ['category' => 'question']) }}" class="btn {{ $category === 'question' ? 'btn-primary' : 'btn-secondary' }}" style="font-size: 12px; padding: 6px 14px;">❓ Questions</a>
    <a href="{{ route('admin.bot_messages', ['category' => 'follow_up']) }}" class="btn {{ $category === 'follow_up' ? 'btn-primary' : 'btn-secondary' }}" style="font-size: 12px; padding: 6px 14px;">💬 Follow-Ups</a>
</div>

<!-- Table Card -->
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fa-solid fa-list"></i> Pre-configured Message Templates ({{ $messages->total() }})</span>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="width: 70px;">ID</th>
                    <th style="width: 130px;">Category</th>
                    <th>Message Content Template</th>
                    <th style="width: 110px;">Status</th>
                    <th style="width: 140px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                <tr>
                    <td style="font-weight: 600; color: var(--text-muted);">#{{ $msg->id }}</td>
                    <td>
                        @if($msg->category === 'greeting')
                            <span class="badge" style="background: #e0e7ff; color: #4338ca;">👋 Greeting</span>
                        @elseif($msg->category === 'flirty')
                            <span class="badge" style="background: #fce7f3; color: #db2777;">💖 Flirty</span>
                        @elseif($msg->category === 'question')
                            <span class="badge" style="background: #fef3c7; color: #d97706;">❓ Question</span>
                        @else
                            <span class="badge" style="background: #e0f2fe; color: #0284c7;">💬 Follow-up</span>
                        @endif
                    </td>
                    <td style="font-size: 13.5px; font-weight: 500; color: var(--text-primary);">
                        "{{ $msg->body }}"
                    </td>
                    <td>
                        <form action="{{ route('admin.bot_messages.update', $msg->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="toggle_status" value="1">
                            <button type="submit" class="badge {{ $msg->is_active ? 'badge-active' : 'badge-suspended' }}" style="border: none; cursor: pointer;" title="Click to toggle">
                                {{ $msg->is_active ? 'Active' : 'Paused' }}
                            </button>
                        </form>
                    </td>
                    <td style="text-align: right;">
                        <form action="{{ route('admin.bot_messages.delete', $msg->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this template permanently?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 4px 10px; font-size: 11.5px;">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 32px; color: var(--text-muted);">
                        <i class="fa-solid fa-inbox" style="font-size: 28px; margin-bottom: 8px; display: block;"></i>
                        No message templates found for this filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($messages->hasPages())
    <div class="pagination">
        <div>Showing {{ $messages->firstItem() }} to {{ $messages->lastItem() }} of {{ $messages->total() }} templates</div>
        <div>{{ $messages->links() }}</div>
    </div>
    @endif
</div>

<!-- Modal: Add Message Template -->
<div id="addMessageModal" class="modal-backdrop">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fa-solid fa-plus-circle"></i> Add Bot Message Template</h3>
            <button onclick="closeModal('addMessageModal')" class="btn-close">&times;</button>
        </div>
        <form action="{{ route('admin.bot_messages.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-control" required>
                        <option value="greeting">👋 Greeting & Opener</option>
                        <option value="flirty">💖 Flirty & Charming Hook</option>
                        <option value="question">❓ Question / Icebreaker</option>
                        <option value="follow_up">💬 Staggered Follow-up Message</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Message Body (Text with Emojis)</label>
                    <textarea name="body" class="form-control" rows="4" placeholder="e.g. Hey handsome 😊 Loved your vibe! Coffee or late night drives? ☕🚗" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('addMessageModal')" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Save Template</button>
            </div>
        </form>
    </div>
</div>
@endsection
