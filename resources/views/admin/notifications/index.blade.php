@extends('admin.layouts.app')

@section('title', 'Push Notifications')
@section('page_title', 'Push Notifications Broadcast')

@section('content')
    <!-- KPI STATS CARDS -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="card" style="padding: 18px; display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16, 185, 129, 0.12); color: #10B981; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fa-solid fa-bell"></i>
            </div>
            <div>
                <div style="font-size: 12px; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Active FCM Tokens</div>
                <div style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin-top: 2px;">{{ number_format($stats['active_tokens'] ?? 0) }}</div>
                <div style="font-size: 11px; color: #10B981; margin-top: 2px;"><i class="fa-solid fa-check"></i> Ready to receive</div>
            </div>
        </div>

        <div class="card" style="padding: 18px; display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(59, 130, 246, 0.12); color: #3B82F6; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fa-solid fa-mobile-screen"></i>
            </div>
            <div>
                <div style="font-size: 12px; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Devices</div>
                <div style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin-top: 2px;">{{ number_format($stats['total_devices'] ?? 0) }}</div>
                <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Registered devices</div>
            </div>
        </div>

        <div class="card" style="padding: 18px; display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(139, 92, 246, 0.12); color: #8B5CF6; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fa-brands fa-android"></i>
            </div>
            <div>
                <div style="font-size: 12px; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Android Devices</div>
                <div style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin-top: 2px;">{{ number_format($stats['android_devices'] ?? 0) }}</div>
                <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Android app installs</div>
            </div>
        </div>

        <div class="card" style="padding: 18px; display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(236, 72, 153, 0.12); color: #EC4899; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <div style="font-size: 12px; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">Total Users</div>
                <div style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin-top: 2px;">{{ number_format($stats['total_users'] ?? 0) }}</div>
                <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">{{ $stats['users_with_devices'] ?? 0 }} with devices</div>
            </div>
        </div>
    </div>

    <!-- MAIN COMPOSER & PREVIEW GRID -->
    <div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 24px; align-items: start; margin-bottom: 28px;">
        
        <!-- COMPOSE CARD -->
        <div class="card">
            <div class="card-header">
                <div>
                    <span class="card-title"><i class="fa-solid fa-paper-plane" style="color: var(--primary); margin-right: 8px;"></i> Send Push Notification</span>
                    <p style="font-size: 12.5px; color: var(--text-muted); margin-top: 4px;">Reach all users via FCM topic 'all', or target single or multiple members.</p>
                </div>
            </div>
            <div style="padding: 22px;">
                <form id="pushNotificationForm" action="{{ route('admin.notifications.send') }}" method="POST">
                    @csrf

                    <!-- TARGET AUDIENCE SELECTOR -->
                    <div style="margin-bottom: 22px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 10px;">
                            Recipient Audience <span style="color: #ef4444;">*</span>
                        </label>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                            <!-- Option 1: All Members -->
                            <label class="audience-option-card" id="card_all" onclick="selectAudience('all')">
                                <input type="radio" name="target_type" value="all" checked style="display: none;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(236,72,153,0.15); color: #ec4899; display: flex; align-items: center; justify-content: center;">
                                        <i class="fa-solid fa-bullhorn"></i>
                                    </div>
                                    <span class="radio-indicator"><i class="fa-solid fa-check"></i></span>
                                </div>
                                <div style="font-weight: 700; font-size: 13.5px; margin-top: 10px; color: var(--text-primary);">All Members</div>
                                <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 3px;">Broadcast via <code>all</code> topic</div>
                            </label>

                            <!-- Option 2: Single Member -->
                            <label class="audience-option-card" id="card_single" onclick="selectAudience('single')">
                                <input type="radio" name="target_type" value="single" style="display: none;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(59,130,246,0.15); color: #3b82f6; display: flex; align-items: center; justify-content: center;">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <span class="radio-indicator"><i class="fa-solid fa-check"></i></span>
                                </div>
                                <div style="font-weight: 700; font-size: 13.5px; margin-top: 10px; color: var(--text-primary);">Single Member</div>
                                <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 3px;">Select 1 specific user</div>
                            </label>

                            <!-- Option 3: Multiple Members -->
                            <label class="audience-option-card" id="card_multiple" onclick="selectAudience('multiple')">
                                <input type="radio" name="target_type" value="multiple" style="display: none;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(139,92,246,0.15); color: #8b5cf6; display: flex; align-items: center; justify-content: center;">
                                        <i class="fa-solid fa-users-viewfinder"></i>
                                    </div>
                                    <span class="radio-indicator"><i class="fa-solid fa-check"></i></span>
                                </div>
                                <div style="font-weight: 700; font-size: 13.5px; margin-top: 10px; color: var(--text-primary);">Multiple Members</div>
                                <div style="font-size: 11.5px; color: var(--text-muted); margin-top: 3px;">Select specific group</div>
                            </label>
                        </div>
                    </div>

                    <!-- AUDIENCE: ALL TOPIC INFO BANNER -->
                    <div id="section_all" style="margin-bottom: 20px; padding: 14px 16px; border-radius: 12px; background: rgba(236,72,153,0.06); border: 1px dashed rgba(236,72,153,0.3); display: flex; align-items: flex-start; gap: 12px;">
                        <i class="fa-solid fa-satellite-dish" style="color: #ec4899; font-size: 18px; margin-top: 2px;"></i>
                        <div style="font-size: 12.5px; color: var(--text-secondary); line-height: 1.5;">
                            <strong style="color: var(--text-primary);">Topic Broadcast:</strong> This will publish an FCM message to the <code style="background: rgba(236,72,153,0.1); color: #ec4899; padding: 2px 6px; border-radius: 4px; font-weight: 600;">all</code> topic. Every user who has installed <strong>Desi Saheli</strong> and is connected to Google Services will instantly receive this notification.
                        </div>
                    </div>

                    <!-- AUDIENCE: SINGLE MEMBER SELECTOR -->
                    <div id="section_single" style="display: none; margin-bottom: 20px;">
                        <label for="single_user_id" style="display: block; font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">
                            Select Member <span style="color: #ef4444;">*</span>
                        </label>
                        <select id="single_user_id" name="user_id" class="form-control" style="width: 100%; padding: 11px 14px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-body); color: var(--text-primary); font-size: 13.5px;">
                            <option value="">-- Choose a user --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" data-name="{{ $u->name }}" data-devices="{{ $u->active_devices_count }}">
                                    #{{ $u->id }} - {{ $u->name }} ({{ $u->phone ?: $u->email }}) • {{ $u->active_devices_count }} active {{ Str::plural('device', $u->active_devices_count) }}
                                </option>
                            @endforeach
                        </select>
                        <p style="font-size: 11.5px; color: var(--text-muted); margin-top: 6px;">Shows members and active registered devices.</p>
                    </div>

                    <!-- AUDIENCE: MULTIPLE MEMBERS SELECTOR -->
                    <div id="section_multiple" style="display: none; margin-bottom: 20px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                            <label style="font-size: 13px; font-weight: 600; color: var(--text-primary);">
                                Choose Target Members (<span id="selectedCount">0</span> selected) <span style="color: #ef4444;">*</span>
                            </label>
                            <div>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="toggleSelectAllUsers(true)" style="padding: 4px 10px; font-size: 11.5px;">Select All</button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="toggleSelectAllUsers(false)" style="padding: 4px 10px; font-size: 11.5px; margin-left: 4px;">Clear</button>
                            </div>
                        </div>

                        <!-- Search input for filter -->
                        <div style="position: relative; margin-bottom: 8px;">
                            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 11px; color: var(--text-muted); font-size: 12px;"></i>
                            <input type="text" id="userSearchInput" placeholder="Filter members by name or phone..." onkeyup="filterUserList()" style="width: 100%; padding: 8px 12px 8px 34px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-body); color: var(--text-primary); font-size: 12.5px;">
                        </div>

                        <!-- Scrollable list of user checkboxes -->
                        <div id="usersCheckboxList" style="max-height: 200px; overflow-y: auto; border: 1px solid var(--border-color); border-radius: 10px; padding: 8px; background: var(--bg-body);">
                            @foreach($users as $u)
                                <label class="user-check-item" data-search="{{ strtolower($u->name . ' ' . $u->phone . ' ' . $u->email) }}" style="display: flex; align-items: center; gap: 10px; padding: 7px 10px; border-radius: 6px; cursor: pointer; transition: background 0.15s;">
                                    <input type="checkbox" name="user_ids[]" value="{{ $u->id }}" class="user-checkbox" onchange="updateSelectedCount()">
                                    <div style="width: 28px; height: 28px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold; flex-shrink: 0;">
                                        {{ substr($u->name, 0, 1) }}
                                    </div>
                                    <div style="min-width: 0; flex: 1;">
                                        <div style="font-size: 13px; font-weight: 600; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $u->name }}</div>
                                        <div style="font-size: 11px; color: var(--text-muted);">#{{ $u->id }} • {{ $u->phone ?: $u->email }}</div>
                                    </div>
                                    <span style="font-size: 10.5px; padding: 2px 7px; border-radius: 12px; background: {{ $u->active_devices_count > 0 ? 'rgba(16,185,129,0.15)' : 'rgba(148,163,184,0.15)' }}; color: {{ $u->active_devices_count > 0 ? '#10B981' : 'var(--text-muted)' }}; font-weight: 600;">
                                        {{ $u->active_devices_count }} {{ Str::plural('dev', $u->active_devices_count) }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- NOTIFICATION TITLE -->
                    <div style="margin-bottom: 18px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                            <label for="notif_title" style="font-size: 13px; font-weight: 600; color: var(--text-primary);">
                                Notification Title <span style="color: #ef4444;">*</span>
                            </label>
                            <span id="titleCounter" style="font-size: 11.5px; color: var(--text-muted);">0 / 100</span>
                        </div>
                        <input type="text" id="notif_title" name="title" required maxlength="100"
                            placeholder="e.g., Someone liked your profile! 💕"
                            oninput="updatePreview(); updateCounter('notif_title', 'titleCounter', 100)"
                            style="width: 100%; padding: 11px 14px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-body); color: var(--text-primary); font-size: 14px; font-weight: 500;">
                    </div>

                    <!-- NOTIFICATION MESSAGE BODY -->
                    <div style="margin-bottom: 18px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                            <label for="notif_body" style="font-size: 13px; font-weight: 600; color: var(--text-primary);">
                                Message Body <span style="color: #ef4444;">*</span>
                            </label>
                            <span id="bodyCounter" style="font-size: 11.5px; color: var(--text-muted);">0 / 300</span>
                        </div>
                        <textarea id="notif_body" name="body" required rows="3" maxlength="300"
                            placeholder="e.g., Open Desi Saheli now to see your match and send a warm greeting..."
                            oninput="updatePreview(); updateCounter('notif_body', 'bodyCounter', 300)"
                            style="width: 100%; padding: 11px 14px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-body); color: var(--text-primary); font-size: 13.5px; line-height: 1.5; resize: vertical;"></textarea>
                    </div>

                    <!-- ACTION / DEEP LINK INTENT -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 24px;">
                        <div>
                            <label for="action_type" style="display: block; font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 6px;">
                                In-App Action
                            </label>
                            <select id="action_type" name="action_type" onchange="updatePreview()" class="form-control" style="width: 100%; padding: 10px 12px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-body); color: var(--text-primary); font-size: 13px;">
                                <option value="general">Default (Open App)</option>
                                <option value="open_chat">Open Messages / Chat</option>
                                <option value="open_profile">Discovery / Find Profiles</option>
                                <option value="open_store">Coin Store (Recharge)</option>
                                <option value="open_subscription">VIP Membership Superpowers</option>
                            </select>
                        </div>
                        <div>
                            <label for="action_data" style="display: block; font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 6px;">
                                Extra Action Data (Optional)
                            </label>
                            <input type="text" id="action_data" name="action_data" placeholder="e.g., promo_code, user_id" style="width: 100%; padding: 10px 12px; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-body); color: var(--text-primary); font-size: 13px;">
                        </div>
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <button type="submit" id="sendBtn" class="btn btn-primary" style="padding: 12px 24px; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-paper-plane"></i> Send Push Notification
                        </button>
                        <button type="reset" class="btn btn-secondary" onclick="resetForm()" style="padding: 12px 18px; font-size: 14px;">
                            Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- LIVE PHONE PREVIEW CARD -->
        <div>
            <div class="card" style="position: sticky; top: 90px;">
                <div class="card-header">
                    <span class="card-title"><i class="fa-solid fa-mobile" style="color: var(--primary); margin-right: 8px;"></i> Android Lock Screen Preview</span>
                </div>
                <div style="padding: 24px; background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 100%); border-radius: 0 0 16px 16px; min-height: 400px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    
                    <!-- PHONE MOCKUP -->
                    <div style="width: 100%; max-width: 330px; background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 20px; padding: 16px; box-shadow: 0 20px 30px rgba(0,0,0,0.4);">
                        
                        <!-- NOTIFICATION CARD -->
                        <div style="background: rgba(255, 255, 255, 0.95); border-radius: 14px; padding: 14px; color: #0f172a; box-shadow: 0 4px 14px rgba(0,0,0,0.15);">
                            <!-- APP HEADER -->
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 22px; height: 22px; border-radius: 6px; background: linear-gradient(135deg, #ec4899, #8b5cf6); display: flex; align-items: center; justify-content: center; color: white; font-size: 11px;">
                                        <i class="fa-solid fa-heart"></i>
                                    </div>
                                    <span style="font-weight: 700; font-size: 12.5px; color: #1e293b;">Desi Saheli</span>
                                    <span style="font-size: 10.5px; color: #64748b;">• now</span>
                                </div>
                                <i class="fa-solid fa-chevron-down" style="font-size: 11px; color: #94a3b8;"></i>
                            </div>

                            <!-- TITLE -->
                            <div id="previewTitle" style="font-size: 13.5px; font-weight: 700; color: #0f172a; margin-bottom: 3px; word-break: break-word;">
                                Someone liked your profile! 💕
                            </div>

                            <!-- BODY -->
                            <div id="previewBody" style="font-size: 12px; color: #475569; line-height: 1.45; word-break: break-word;">
                                Open Desi Saheli now to see your match and send a warm greeting...
                            </div>

                            <!-- ACTION BADGE -->
                            <div id="previewActionBadge" style="margin-top: 10px; display: inline-flex; align-items: center; gap: 5px; font-size: 10.5px; font-weight: 600; color: #ec4899; background: rgba(236,72,153,0.1); padding: 3px 8px; border-radius: 6px;">
                                <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 9px;"></i>
                                <span id="previewActionText">Open App</span>
                            </div>
                        </div>

                    </div>

                    <div style="margin-top: 18px; color: rgba(255, 255, 255, 0.6); font-size: 11.5px; text-align: center;">
                        <i class="fa-solid fa-circle-info"></i> Displays with sound & vibration on user's device
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- RECENT PUSH BROADCAST HISTORY -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fa-solid fa-clock-rotate-left" style="color: var(--primary); margin-right: 8px;"></i> Broadcast History & Audit Logs</span>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Admin Author</th>
                        <th>Audience Target</th>
                        <th>Notification Title & Body</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentCampaigns as $c)
                        @php
                            $details = json_decode($c->details, true) ?? [];
                        @endphp
                        <tr>
                            <td style="white-space: nowrap; font-size: 12px;">
                                <div><strong>{{ $c->created_at->format('M d, Y') }}</strong></div>
                                <div style="color: var(--text-muted);">{{ $c->created_at->format('h:i A') }}</div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 26px; height: 26px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold;">
                                        {{ substr($c->adminUser?->name ?? 'A', 0, 1) }}
                                    </div>
                                    <span style="font-weight: 600; font-size: 13px;">{{ $c->adminUser?->name ?? 'Admin' }}</span>
                                </div>
                            </td>
                            <td>
                                @if($c->action === 'push_notification_topic_all')
                                    <span class="badge" style="background: rgba(236,72,153,0.15); color: #ec4899; font-weight: 700;">
                                        <i class="fa-solid fa-bullhorn"></i> All Members (all topic)
                                    </span>
                                @elseif($c->action === 'push_notification_single')
                                    <span class="badge" style="background: rgba(59,130,246,0.15); color: #3b82f6; font-weight: 700;">
                                        <i class="fa-solid fa-user"></i> Single ({{ $details['target'] ?? "User #{$c->target_id}" }})
                                    </span>
                                @elseif($c->action === 'push_notification_multiple')
                                    <span class="badge" style="background: rgba(139,92,246,0.15); color: #8b5cf6; font-weight: 700;">
                                        <i class="fa-solid fa-users"></i> Multiple ({{ $details['target_count'] ?? 'Group' }} users)
                                    </span>
                                @else
                                    <span class="badge">{{ $c->action }}</span>
                                @endif
                            </td>
                            <td style="max-width: 320px;">
                                <div style="font-weight: 700; font-size: 13px; color: var(--text-primary); margin-bottom: 2px;">
                                    {{ $details['title'] ?? 'Notification' }}
                                </div>
                                <div style="font-size: 12px; color: var(--text-muted); line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $details['body'] ?? '-' }}
                                </div>
                            </td>
                            <td>
                                @if(isset($details['total_sent']))
                                    <span style="font-size: 11.5px; color: #10B981; font-weight: 600;">
                                        <i class="fa-solid fa-circle-check"></i> {{ $details['total_sent'] }} delivered
                                    </span>
                                @elseif(isset($details['devices_sent']))
                                    <span style="font-size: 11.5px; color: #10B981; font-weight: 600;">
                                        <i class="fa-solid fa-circle-check"></i> {{ $details['devices_sent'] }}/{{ $details['devices_targeted'] ?? 0 }} devices
                                    </span>
                                @elseif(isset($details['result']['success']) && $details['result']['success'])
                                    <span style="font-size: 11.5px; color: #10B981; font-weight: 600;">
                                        <i class="fa-solid fa-circle-check"></i> Broadcast Sent
                                    </span>
                                @else
                                    <span style="font-size: 11.5px; color: var(--text-muted);">
                                        Dispatched
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 24px;">No push notification broadcasts sent yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- CUSTOM STYLES & INTERACTIVE SCRIPT -->
    <style>
        .audience-option-card {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 14px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--bg-body);
            position: relative;
        }
        .audience-option-card:hover {
            border-color: rgba(236, 72, 153, 0.4);
            transform: translateY(-2px);
        }
        .audience-option-card.active {
            border-color: #ec4899;
            background: rgba(236, 72, 153, 0.05);
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.12);
        }
        .audience-option-card .radio-indicator {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: transparent;
            transition: all 0.2s;
        }
        .audience-option-card.active .radio-indicator {
            background: #ec4899;
            border-color: #ec4899;
            color: white;
        }
        .user-check-item:hover {
            background: rgba(236, 72, 153, 0.08);
        }
    </style>

    <script>
        function selectAudience(type) {
            // Update cards visual state
            ['all', 'single', 'multiple'].forEach(function(item) {
                var card = document.getElementById('card_' + item);
                var section = document.getElementById('section_' + item);
                var radio = card.querySelector('input[type="radio"]');

                if (item === type) {
                    card.classList.add('active');
                    radio.checked = true;
                    if (section) section.style.display = 'block';
                } else {
                    card.classList.remove('active');
                    radio.checked = false;
                    if (section) section.style.display = 'none';
                }
            });

            // Adjust form validation requirements
            var singleSelect = document.getElementById('single_user_id');
            if (type === 'single') {
                singleSelect.setAttribute('required', 'required');
            } else {
                singleSelect.removeAttribute('required');
            }
        }

        // Initialize active state on page load
        document.addEventListener('DOMContentLoaded', function() {
            selectAudience('all');
        });

        function updateCounter(inputId, counterId, max) {
            var input = document.getElementById(inputId);
            var counter = document.getElementById(counterId);
            if (input && counter) {
                counter.innerText = input.value.length + ' / ' + max;
            }
        }

        function updatePreview() {
            var titleInput = document.getElementById('notif_title');
            var bodyInput = document.getElementById('notif_body');
            var actionSelect = document.getElementById('action_type');

            var previewTitle = document.getElementById('previewTitle');
            var previewBody = document.getElementById('previewBody');
            var previewActionText = document.getElementById('previewActionText');

            if (titleInput && previewTitle) {
                previewTitle.innerText = titleInput.value.trim() || 'Notification Title';
            }

            if (bodyInput && previewBody) {
                previewBody.innerText = bodyInput.value.trim() || 'Your notification message body will appear here on user lock-screens and notification shades.';
            }

            if (actionSelect && previewActionText) {
                var selectedText = actionSelect.options[actionSelect.selectedIndex].text;
                previewActionText.innerText = selectedText;
            }
        }

        function filterUserList() {
            var filter = document.getElementById('userSearchInput').value.toLowerCase();
            var items = document.querySelectorAll('.user-check-item');
            items.forEach(function(item) {
                var search = item.getAttribute('data-search') || '';
                if (search.indexOf(filter) > -1) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function toggleSelectAllUsers(select) {
            var checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(function(cb) {
                var parent = cb.closest('.user-check-item');
                if (parent && parent.style.display !== 'none') {
                    cb.checked = select;
                }
            });
            updateSelectedCount();
        }

        function updateSelectedCount() {
            var checked = document.querySelectorAll('.user-checkbox:checked').length;
            var counter = document.getElementById('selectedCount');
            if (counter) {
                counter.innerText = checked;
            }
        }

        function resetForm() {
            setTimeout(function() {
                selectAudience('all');
                updatePreview();
                updateCounter('notif_title', 'titleCounter', 100);
                updateCounter('notif_body', 'bodyCounter', 300);
                updateSelectedCount();
            }, 50);
        }

        // Confirmation before sending to all members
        document.getElementById('pushNotificationForm').addEventListener('submit', function(e) {
            var targetType = document.querySelector('input[name="target_type"]:checked').value;
            if (targetType === 'all') {
                if (!confirm('Are you sure you want to broadcast this push notification to ALL members via the "all" topic?')) {
                    e.preventDefault();
                    return false;
                }
            } else if (targetType === 'multiple') {
                var count = document.querySelectorAll('.user-checkbox:checked').length;
                if (count === 0) {
                    alert('Please select at least one recipient user for multiple members push.');
                    e.preventDefault();
                    return false;
                }
                if (!confirm('Send push notification to ' + count + ' selected members?')) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    </script>
@endsection
