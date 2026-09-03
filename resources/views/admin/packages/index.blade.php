@extends('admin.layouts.app')

@section('title', 'Coin Packages')
@section('page_title', 'In-App Coin Store Packages')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title">Coin Store Packages ({{ count($packages) }})</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="openModal('packageModal')">
                <i class="fa-solid fa-plus"></i> Add Package
            </button>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Package Name</th>
                        <th>Coins</th>
                        <th>Bonus Coins</th>
                        <th>Price</th>
                        <th>Product SKU</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $p)
                        <tr>
                            <td><strong>{{ $p->name }}</strong></td>
                            <td><strong>{{ number_format($p->coins) }}</strong> 🪙</td>
                            <td>+{{ number_format($p->bonus_coins) }}</td>
                            <td><strong>₹{{ number_format($p->price, 0) }}</strong></td>
                            <td><code>{{ $p->google_product_id }}</code></td>
                            <td>
                                <span class="badge {{ $p->is_active ? 'badge-active' : 'badge-suspended' }}">
                                    {{ $p->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="openEditPackageModal({{ json_encode($p) }})" title="Edit Package">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>
                                <form action="{{ route('admin.packages.delete', $p->id) }}" method="POST" onsubmit="return confirm('Delete package {{ addslashes($p->name) }}?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete Package"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 25px;">No coin packages created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('modals')
    <!-- MODAL: ADD PACKAGE -->
    <div id="packageModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h3 style="font-size: 15px; font-weight: 600;">Add Coin Package</h3>
                <button onclick="closeModal('packageModal')" style="background: none; border: none; font-size: 18px; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>
            <form action="{{ route('admin.packages.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Package Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Popular Pack" required>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group">
                            <label>Coins Amount</label>
                            <input type="number" name="coins" class="form-control" placeholder="100" required min="1">
                        </div>
                        <div class="form-group">
                            <label>Bonus Coins</label>
                            <input type="number" name="bonus_coins" class="form-control" placeholder="10" value="0" min="0">
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group">
                            <label>Price (INR ₹)</label>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="99.00" required min="0">
                        </div>
                        <div class="form-group">
                            <label>Product SKU / ID</label>
                            <input type="text" name="google_product_id" class="form-control" placeholder="com.soulconnect.coins_100" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('packageModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Package</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: EDIT PACKAGE -->
    <div id="editPackageModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h3 style="font-size: 15px; font-weight: 600;">Edit Coin Package</h3>
                <button onclick="closeModal('editPackageModal')" style="background: none; border: none; font-size: 18px; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>
            <form id="editPackageForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Package Name</label>
                        <input type="text" id="edit_pkg_name" name="name" class="form-control" required>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group">
                            <label>Coins Amount</label>
                            <input type="number" id="edit_pkg_coins" name="coins" class="form-control" required min="1">
                        </div>
                        <div class="form-group">
                            <label>Bonus Coins</label>
                            <input type="number" id="edit_pkg_bonus" name="bonus_coins" class="form-control" min="0">
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group">
                            <label>Price (INR ₹)</label>
                            <input type="number" step="0.01" id="edit_pkg_price" name="price" class="form-control" required min="0">
                        </div>
                        <div class="form-group">
                            <label>Product SKU / ID</label>
                            <input type="text" id="edit_pkg_sku" name="google_product_id" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top: 10px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" id="edit_pkg_active" name="is_active" value="1">
                            <span style="font-weight: 500;">Active in Coin Store</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editPackageModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function openEditPackageModal(pkg) {
        document.getElementById('editPackageForm').action = "/admin/packages/" + pkg.id;
        document.getElementById('edit_pkg_name').value = pkg.name;
        document.getElementById('edit_pkg_coins').value = pkg.coins;
        document.getElementById('edit_pkg_bonus').value = pkg.bonus_coins || 0;
        document.getElementById('edit_pkg_price').value = pkg.price;
        document.getElementById('edit_pkg_sku').value = pkg.google_product_id;
        document.getElementById('edit_pkg_active').checked = pkg.is_active ? true : false;
        openModal('editPackageModal');
    }
</script>
@endsection
