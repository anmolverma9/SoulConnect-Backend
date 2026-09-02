@extends('admin.layouts.app')

@section('title', 'Virtual Gifts')
@section('page_title', 'Virtual Gift Catalog')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title">Virtual Gift Catalog ({{ count($gifts) }})</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="openModal('giftModal')">
                <i class="fa-solid fa-plus"></i> Add Gift
            </button>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Gift Name</th>
                        <th>Coin Price</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gifts as $g)
                        <tr>
                            <td style="font-size: 26px;">{{ $g->icon_url }}</td>
                            <td><strong>{{ $g->name }}</strong></td>
                            <td><strong>{{ $g->coin_price }}</strong> 🪙</td>
                            <td><span class="badge badge-active">{{ ucfirst($g->category ?: 'Standard') }}</span></td>
                            <td><span class="badge badge-active">Active</span></td>
                            <td>
                                <form action="{{ route('admin.gifts.delete', $g->id) }}" method="POST" onsubmit="return confirm('Delete gift {{ addslashes($g->name) }}?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 25px;">No virtual gifts created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('modals')
    <!-- MODAL: ADD GIFT -->
    <div id="giftModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h3 style="font-size: 15px; font-weight: 600;">Add Virtual Gift</h3>
                <button onclick="closeModal('giftModal')" style="background: none; border: none; font-size: 18px; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>
            <form action="{{ route('admin.gifts.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Gift Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Red Rose" required>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group">
                            <label>Emoji / Icon</label>
                            <input type="text" name="icon_url" class="form-control" placeholder="🌹" required>
                        </div>
                        <div class="form-group">
                            <label>Coin Cost</label>
                            <input type="number" name="coin_price" class="form-control" placeholder="25" required min="1">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" class="form-control">
                            <option value="romance">Romance</option>
                            <option value="fun">Fun</option>
                            <option value="luxury">Luxury</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('giftModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Gift</button>
                </div>
            </form>
        </div>
    </div>
@endsection
