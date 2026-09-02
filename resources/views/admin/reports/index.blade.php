@extends('admin.layouts.app')

@section('title', 'Abuse Reports')
@section('page_title', 'Abuse & Safety Reports')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title">User Reports & Moderation (Total: {{ $reports->total() }})</span>
            <form action="{{ route('admin.reports') }}" method="GET" style="display: flex; gap: 10px;">
                <div class="input-group">
                    <select name="status" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="reviewing" {{ request('status') == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="dismissed" {{ request('status') == 'dismissed' ? 'selected' : '' }}>Dismissed</option>
                    </select>
                </div>
                @if(request('status'))
                    <a href="{{ route('admin.reports') }}" class="btn btn-secondary btn-sm">Reset</a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>Reporter</th>
                        <th>Reported User</th>
                        <th>Reason</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $r)
                        <tr>
                            <td><code>#{{ $r->id }}</code></td>
                            <td>User #{{ $r->reporter_id }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $r->reported_id) }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">
                                    {{ $r->reported?->name ?? 'User #' . $r->reported_id }}
                                </a>
                            </td>
                            <td><strong>{{ ucfirst($r->reason) }}</strong></td>
                            <td style="font-size: 13px; max-width: 250px;">{{ $r->description ?: 'No details provided' }}</td>
                            <td><span class="badge badge-{{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
                            <td style="font-size: 12px; color: var(--text-secondary);">{{ $r->created_at->format('d M Y') }}</td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" onclick="openReportModal({{ $r->id }}, '{{ $r->status }}', '{{ addslashes($r->resolution_notes ?? '') }}')">
                                    Review
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 25px;">No abuse reports found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $reports->appends(request()->query())->links('admin.layouts.pagination') }}
    </div>
@endsection

@section('modals')
    <!-- MODAL: REVIEW REPORT -->
    <div id="reportModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h3 style="font-size: 15px; font-weight: 600;">Review Abuse Report</h3>
                <button onclick="closeModal('reportModal')" style="background: none; border: none; font-size: 18px; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>
            <form id="reportForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Resolution Status</label>
                        <select name="status" id="reportStatusSelect" class="form-control">
                            <option value="resolved">Mark Resolved</option>
                            <option value="dismissed">Dismiss Report</option>
                            <option value="reviewing">Mark In-Review</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Resolution Notes</label>
                        <textarea name="resolution_notes" id="reportNotes" class="form-control" rows="3" placeholder="Action taken..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('reportModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openReportModal(reportId, currentStatus, currentNotes) {
            document.getElementById('reportStatusSelect').value = currentStatus;
            document.getElementById('reportNotes').value = currentNotes;
            document.getElementById('reportForm').action = `/admin/reports/${reportId}`;
            openModal('reportModal');
        }
    </script>
@endsection
