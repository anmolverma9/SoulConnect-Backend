<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soul Connect — Admin Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg-primary: #f8fafc;
            --bg-surface: #ffffff;
            --bg-sidebar: #ffffff;
            --border-color: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --primary-light: #eef2ff;
            --danger: #ef4444;
            --danger-light: #fef2f2;
            --success: #10b981;
            --success-light: #ecfdf5;
            --warning: #f59e0b;
            --warning-light: #fffbeb;
            --card-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.07), 0 1px 2px 0 rgba(0, 0, 0, 0.04);
            --modal-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        html.dark {
            --bg-primary: #0f172a;
            --bg-surface: #1e293b;
            --bg-sidebar: #1e293b;
            --border-color: #334155;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --primary: #818cf8;
            --primary-hover: #6366f1;
            --primary-light: rgba(99, 102, 241, 0.15);
            --danger: #f87171;
            --danger-light: rgba(239, 68, 68, 0.15);
            --success: #34d399;
            --success-light: rgba(16, 185, 129, 0.15);
            --warning: #fbbf24;
            --warning-light: rgba(245, 158, 11, 0.15);
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
            --modal-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 40;
        }

        .sidebar-header {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .logo-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #ec4899, #8b5cf6);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .logo-text {
            font-weight: 700;
            font-size: 18px;
            letter-spacing: -0.5px;
        }

        .sidebar-menu {
            padding: 16px 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            color: var(--text-secondary);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
        }

        .nav-item:hover {
            background-color: var(--primary-light);
            color: var(--primary);
        }

        .nav-item.active {
            background-color: var(--primary);
            color: #ffffff;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Main Content */
        .main-wrapper {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            height: 70px;
            background: var(--bg-surface);
            border-bottom: 1px solid var(--border-color);
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 30;
        }

        .header-title {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn-theme {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .btn-logout {
            background: var(--danger-light);
            color: var(--danger);
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
        }

        .content {
            padding: 32px;
            flex: 1;
        }

        /* Stat Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--bg-surface);
            padding: 22px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .stat-sub {
            font-size: 12px;
            color: var(--success);
            font-weight: 500;
        }

        /* Card Section */
        .card {
            background: var(--bg-surface);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            margin-bottom: 32px;
            overflow: hidden;
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
        }

        /* Table */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 14px;
        }

        th {
            background: var(--bg-primary);
            padding: 12px 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-color);
        }

        td {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background-color: var(--primary-light);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-active { background: var(--success-light); color: var(--success); }
        .badge-suspended { background: var(--warning-light); color: var(--warning); }
        .badge-banned, .badge-deleted { background: var(--danger-light); color: var(--danger); }
        .badge-pending { background: var(--warning-light); color: var(--warning); }
        .badge-resolved { background: var(--success-light); color: var(--success); }
        .badge-dismissed { background: var(--bg-primary); color: var(--text-muted); }

        /* Buttons & Forms */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-secondary {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .btn-sm {
            padding: 4px 10px;
            font-size: 12px;
        }

        .input-group {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            padding: 8px 14px;
            border-radius: 8px;
        }

        .input-group input, .input-group select {
            background: transparent;
            border: none;
            outline: none;
            color: var(--text-primary);
            font-size: 13px;
            width: 100%;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 14px;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--primary);
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
            padding: 20px;
        }

        .modal {
            background: var(--bg-surface);
            border-radius: 16px;
            width: 100%;
            max-width: 500px;
            border: 1px solid var(--border-color);
            box-shadow: var(--modal-shadow);
            overflow: hidden;
            animation: modalIn 0.2s ease-out;
        }

        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            background: var(--bg-primary);
        }

        /* Login Screen */
        .login-overlay {
            position: fixed;
            inset: 0;
            background: var(--bg-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 200;
            padding: 20px;
        }

        .login-card {
            background: var(--bg-surface);
            padding: 40px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: var(--modal-shadow);
            width: 100%;
            max-width: 420px;
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            padding: 14px 20px;
            border-radius: 10px;
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            display: none;
            align-items: center;
            gap: 10px;
            z-index: 300;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .toast-success { background: #10b981; }
        .toast-error { background: #ef4444; }

        .hidden { display: none !important; }
    </style>
</head>
<body>

    <!-- LOGIN SCREEN -->
    <div id="loginScreen" class="login-overlay">
        <div class="login-card">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                <div class="logo-icon"><i class="fa-solid fa-heart"></i></div>
                <div>
                    <h2 style="font-size: 20px; font-weight: 700;">Soul Connect</h2>
                    <p style="font-size: 13px; color: var(--text-secondary);">Administrator Portal</p>
                </div>
            </div>

            <form id="loginForm" onsubmit="handleLogin(event)">
                <div class="form-group">
                    <label>Admin Email</label>
                    <input type="email" id="loginEmail" class="form-control" placeholder="admin@datingapp.example.com" value="admin@datingapp.example.com" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id="loginPassword" class="form-control" placeholder="••••••••••••" value="Admin@Secure2026!" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 8px;">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> Sign In to Dashboard
                </button>
            </form>
        </div>
    </div>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo-icon"><i class="fa-solid fa-heart"></i></div>
            <span class="logo-text">Soul Connect</span>
        </div>

        <nav class="sidebar-menu">
            <a class="nav-item active" onclick="switchTab('dashboard')"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
            <a class="nav-item" onclick="switchTab('users')"><i class="fa-solid fa-users"></i> Users & Profiles</a>
            <a class="nav-item" onclick="switchTab('reports')"><i class="fa-solid fa-shield-halved"></i> Abuse Reports</a>
            <a class="nav-item" onclick="switchTab('packages')"><i class="fa-solid fa-coins"></i> Coin Packages</a>
            <a class="nav-item" onclick="switchTab('subscriptions')"><i class="fa-solid fa-gem"></i> Subscriptions</a>
            <a class="nav-item" onclick="switchTab('settings')"><i class="fa-solid fa-sliders"></i> App Settings</a>
        </nav>

        <div class="sidebar-footer">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold;">AD</div>
                <div style="font-size: 13px;">
                    <div id="adminName" style="font-weight: 600;">Admin</div>
                    <div style="font-size: 11px; color: var(--text-muted);">Super Admin</div>
                </div>
            </div>
            <button class="btn-theme" onclick="toggleTheme()" title="Toggle Light/Dark Theme">
                <i id="themeIcon" class="fa-solid fa-moon"></i>
            </button>
        </div>
    </aside>

    <!-- MAIN WRAPPER -->
    <div class="main-wrapper">
        <header>
            <h1 class="header-title" id="pageTitle">Dashboard Overview</h1>
            <div class="header-actions">
                <button class="btn-logout" onclick="handleLogout()"><i class="fa-solid fa-power-off"></i> Logout</button>
            </div>
        </header>

        <main class="content">

            <!-- TAB 1: DASHBOARD -->
            <section id="tab-dashboard">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-label">Total Users</span>
                            <div class="stat-icon" style="background: var(--primary-light); color: var(--primary);"><i class="fa-solid fa-users"></i></div>
                        </div>
                        <div class="stat-value" id="statUsers">-</div>
                        <div class="stat-sub"><span id="statNewToday">0</span> joined today</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-label">Mutual Matches</span>
                            <div class="stat-icon" style="background: var(--danger-light); color: var(--danger);"><i class="fa-solid fa-heart"></i></div>
                        </div>
                        <div class="stat-value" id="statMatches">-</div>
                        <div class="stat-sub">Active connections</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-label">Completed Calls</span>
                            <div class="stat-icon" style="background: var(--success-light); color: var(--success);"><i class="fa-solid fa-phone"></i></div>
                        </div>
                        <div class="stat-value" id="statCalls">-</div>
                        <div class="stat-sub">Voice & Video</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-label">Active Subscriptions</span>
                            <div class="stat-icon" style="background: var(--warning-light); color: var(--warning);"><i class="fa-solid fa-gem"></i></div>
                        </div>
                        <div class="stat-value" id="statSubs">-</div>
                        <div class="stat-sub">VIP Premium Members</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-label">Pending Reports</span>
                            <div class="stat-icon" style="background: var(--danger-light); color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        </div>
                        <div class="stat-value" id="statReports">-</div>
                        <div class="stat-sub">Requires Moderation</div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span class="card-title"><i class="fa-solid fa-circle-info" style="color: var(--primary); margin-right: 6px;"></i> System Health & Connectivity</span>
                    </div>
                    <div style="padding: 24px;">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                            <div>
                                <span style="font-size: 12px; color: var(--text-secondary);">REST API Engine</span>
                                <div style="font-weight: 600; margin-top: 4px; color: var(--success);"><i class="fa-solid fa-check-circle"></i> Laravel 12 (PHP 8.4)</div>
                            </div>
                            <div>
                                <span style="font-size: 12px; color: var(--text-secondary);">Database Status</span>
                                <div style="font-weight: 600; margin-top: 4px; color: var(--success);"><i class="fa-solid fa-check-circle"></i> MySQL 8 Connected</div>
                            </div>
                            <div>
                                <span style="font-size: 12px; color: var(--text-secondary);">API Prefix</span>
                                <div style="font-weight: 600; margin-top: 4px;"><code>/api/v1/</code></div>
                            </div>
                            <div>
                                <span style="font-size: 12px; color: var(--text-secondary);">Environment</span>
                                <div style="font-weight: 600; margin-top: 4px;">Production</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- TAB 2: USERS -->
            <section id="tab-users" class="hidden">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">User Accounts & Moderation</span>
                        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                            <div class="input-group">
                                <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted);"></i>
                                <input type="text" id="userSearchInput" placeholder="Search name or email..." oninput="loadUsers()">
                            </div>
                            <div class="input-group">
                                <select id="userStatusFilter" onchange="loadUsers()">
                                    <option value="">All Statuses</option>
                                    <option value="active">Active</option>
                                    <option value="suspended">Suspended</option>
                                    <option value="banned">Banned</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Coins</th>
                                    <th>Subscription</th>
                                    <th>Last Active</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                <tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">Loading users...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- TAB 3: REPORTS -->
            <section id="tab-reports" class="hidden">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Abuse & Safety Reports</span>
                        <div class="input-group">
                            <select id="reportStatusFilter" onchange="loadReports()">
                                <option value="">All Reports</option>
                                <option value="pending" selected>Pending</option>
                                <option value="reviewing">Reviewing</option>
                                <option value="resolved">Resolved</option>
                                <option value="dismissed">Dismissed</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Reporter</th>
                                    <th>Reported User</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="reportsTableBody">
                                <tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">Loading reports...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- TAB 4: COIN PACKAGES -->
            <section id="tab-packages" class="hidden">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">In-App Coin Packages</span>
                        <button class="btn btn-primary btn-sm" onclick="openPackageModal()"><i class="fa-solid fa-plus"></i> Add Package</button>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Package Name</th>
                                    <th>Coins</th>
                                    <th>Bonus Coins</th>
                                    <th>Price</th>
                                    <th>Google Product ID</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="packagesTableBody">
                                <tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">Loading packages...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- TAB 5: SUBSCRIPTIONS -->
            <section id="tab-subscriptions" class="hidden">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Active User Subscriptions</span>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Plan</th>
                                    <th>Status</th>
                                    <th>Starts At</th>
                                    <th>Ends At</th>
                                </tr>
                            </thead>
                            <tbody id="subsTableBody">
                                <tr><td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">Loading subscriptions...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- TAB 6: SETTINGS -->
            <section id="tab-settings" class="hidden">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Application Dynamic Configuration</span>
                    </div>
                    <div style="padding: 24px; max-width: 650px;">
                        <form id="settingsForm" onsubmit="saveSettings(event)">
                            <div class="form-group">
                                <label>Call Rate (Coins per minute)</label>
                                <input type="number" id="setting_call_cost" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Profile Boost Cost (Coins)</label>
                                <input type="number" id="setting_boost_cost" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Boost Duration (Minutes)</label>
                                <input type="number" id="setting_boost_duration" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Super Like Cost (Coins)</label>
                                <input type="number" id="setting_super_like_cost" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Daily Free Likes (Non-subscribers)</label>
                                <input type="number" id="setting_free_likes" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Save Settings</button>
                        </form>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <!-- MODAL: ADJUST WALLET -->
    <div id="walletModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h3 style="font-size: 16px; font-weight: 600;">Adjust User Wallet</h3>
                <button onclick="closeModal('walletModal')" style="background: none; border: none; font-size: 18px; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>
            <form onsubmit="handleWalletAdjust(event)">
                <div class="modal-body">
                    <input type="hidden" id="adjustUserId">
                    <div class="form-group">
                        <label>Target User</label>
                        <input type="text" id="adjustUserName" class="form-control" readonly style="opacity: 0.7;">
                    </div>
                    <div class="form-group">
                        <label>Coin Amount (+ to add, - to deduct)</label>
                        <input type="number" id="adjustAmount" class="form-control" placeholder="e.g. 100 or -50" required>
                    </div>
                    <div class="form-group">
                        <label>Mandatory Audit Reason</label>
                        <input type="text" id="adjustReason" class="form-control" placeholder="e.g. Customer compensation / promotional credit" required minlength="5">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('walletModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Apply Adjustment</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: CHANGE USER STATUS -->
    <div id="statusModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h3 style="font-size: 16px; font-weight: 600;">Update User Status</h3>
                <button onclick="closeModal('statusModal')" style="background: none; border: none; font-size: 18px; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>
            <form onsubmit="handleStatusUpdate(event)">
                <div class="modal-body">
                    <input type="hidden" id="statusUserId">
                    <div class="form-group">
                        <label>Account Status</label>
                        <select id="statusSelect" class="form-control">
                            <option value="active">Active (Normal access)</option>
                            <option value="suspended">Suspended (Temporary hold)</option>
                            <option value="banned">Banned (Permanent block)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Reason for Action</label>
                        <input type="text" id="statusReason" class="form-control" placeholder="e.g. Policy violation / impersonation">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('statusModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Status</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: REVIEW REPORT -->
    <div id="reportModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h3 style="font-size: 16px; font-weight: 600;">Review Abuse Report</h3>
                <button onclick="closeModal('reportModal')" style="background: none; border: none; font-size: 18px; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>
            <form onsubmit="handleReportReview(event)">
                <div class="modal-body">
                    <input type="hidden" id="reportId">
                    <div class="form-group">
                        <label>Resolution Action</label>
                        <select id="reportActionSelect" class="form-control">
                            <option value="resolved">Mark Resolved</option>
                            <option value="dismissed">Dismiss Report</option>
                            <option value="reviewing">Mark In-Review</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Resolution Notes</label>
                        <textarea id="reportNotes" class="form-control" rows="3" placeholder="Notes on actions taken..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('reportModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: COIN PACKAGE -->
    <div id="packageModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h3 style="font-size: 16px; font-weight: 600;" id="pkgModalTitle">Add Coin Package</h3>
                <button onclick="closeModal('packageModal')" style="background: none; border: none; font-size: 18px; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>
            <form onsubmit="handlePackageSave(event)">
                <div class="modal-body">
                    <input type="hidden" id="pkgId">
                    <div class="form-group">
                        <label>Package Name</label>
                        <input type="text" id="pkgName" class="form-control" placeholder="e.g. Starter Pack" required>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group">
                            <label>Coins</label>
                            <input type="number" id="pkgCoins" class="form-control" required min="1">
                        </div>
                        <div class="form-group">
                            <label>Bonus Coins</label>
                            <input type="number" id="pkgBonus" class="form-control" value="0" min="0">
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group">
                            <label>Price (USD)</label>
                            <input type="number" step="0.01" id="pkgPrice" class="form-control" required min="0">
                        </div>
                        <div class="form-group">
                            <label>Google Product ID</label>
                            <input type="text" id="pkgGoogleId" class="form-control" placeholder="com.dating.coins_100" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('packageModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Package</button>
                </div>
            </form>
        </div>
    </div>

    <!-- TOAST NOTIFICATION -->
    <div id="toast" class="toast">
        <i id="toastIcon" class="fa-solid fa-check"></i>
        <span id="toastMsg">Action completed</span>
    </div>

    <script>
        const API_BASE = '/api/v1/admin';
        let authToken = localStorage.getItem('soul_admin_token');

        // Theme Toggle (Light by default, saved in localStorage)
        function initTheme() {
            const theme = localStorage.getItem('soul_admin_theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.getElementById('themeIcon').className = 'fa-solid fa-sun';
            } else {
                document.documentElement.classList.remove('dark');
                document.getElementById('themeIcon').className = 'fa-solid fa-moon';
            }
        }

        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('soul_admin_theme', isDark ? 'dark' : 'light');
            document.getElementById('themeIcon').className = isDark ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
        }

        // Toast Helper
        function showToast(msg, isError = false) {
            const t = document.getElementById('toast');
            t.className = 'toast ' + (isError ? 'toast-error' : 'toast-success');
            document.getElementById('toastMsg').innerText = msg;
            document.getElementById('toastIcon').className = isError ? 'fa-solid fa-triangle-exclamation' : 'fa-solid fa-check';
            t.style.display = 'flex';
            setTimeout(() => { t.style.display = 'none'; }, 3500);
        }

        // Fetch Wrapper with Token
        async function api(url, options = {}) {
            options.headers = options.headers || {};
            options.headers['Accept'] = 'application/json';
            options.headers['Content-Type'] = 'application/json';
            if (authToken) {
                options.headers['Authorization'] = `Bearer ${authToken}`;
            }

            const res = await fetch(url, options);
            const data = await res.json().catch(() => ({}));

            if (res.status === 401) {
                localStorage.removeItem('soul_admin_token');
                authToken = null;
                document.getElementById('loginScreen').style.display = 'flex';
                throw new Error('Unauthenticated');
            }

            if (!res.ok) {
                throw new Error(data.message || 'Request failed');
            }

            return data;
        }

        // Navigation
        function switchTab(tabId) {
            document.querySelectorAll('.sidebar-menu .nav-item').forEach(el => el.classList.remove('active'));
            event?.currentTarget?.classList.add('active');

            ['dashboard', 'users', 'reports', 'packages', 'subscriptions', 'settings'].forEach(t => {
                document.getElementById(`tab-${t}`).classList.add('hidden');
            });
            document.getElementById(`tab-${tabId}`).classList.remove('hidden');

            const titles = {
                dashboard: 'Dashboard Overview',
                users: 'User Moderation',
                reports: 'Abuse Reports',
                packages: 'Coin Packages Management',
                subscriptions: 'Subscriptions',
                settings: 'System Configuration'
            };
            document.getElementById('pageTitle').innerText = titles[tabId] || 'Admin Panel';

            if (tabId === 'dashboard') loadDashboard();
            if (tabId === 'users') loadUsers();
            if (tabId === 'reports') loadReports();
            if (tabId === 'packages') loadPackages();
            if (tabId === 'subscriptions') loadSubscriptions();
            if (tabId === 'settings') loadSettings();
        }

        // Auth
        async function handleLogin(e) {
            e.preventDefault();
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;

            try {
                const res = await fetch(`${API_BASE}/login`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ email, password })
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Login failed');

                authToken = data.data.token;
                localStorage.setItem('soul_admin_token', authToken);
                document.getElementById('adminName').innerText = data.data.admin.name;
                document.getElementById('loginScreen').style.display = 'none';
                showToast('Welcome back, ' + data.data.admin.name);
                loadDashboard();
            } catch (err) {
                showToast(err.message, true);
            }
        }

        async function handleLogout() {
            try {
                await api(`${API_BASE}/logout`, { method: 'POST' });
            } catch (e) {}
            localStorage.removeItem('soul_admin_token');
            authToken = null;
            document.getElementById('loginScreen').style.display = 'flex';
        }

        // Dashboard Stats
        async function loadDashboard() {
            try {
                const res = await api(`${API_BASE}/dashboard`);
                const d = res.data;
                document.getElementById('statUsers').innerText = d.users.total;
                document.getElementById('statNewToday').innerText = d.users.new_today;
                document.getElementById('statMatches').innerText = d.matches.total;
                document.getElementById('statCalls').innerText = d.calls.completed;
                document.getElementById('statSubs').innerText = d.subscriptions.active;
                document.getElementById('statReports').innerText = d.reports.pending;
            } catch (e) {}
        }

        // Users
        async function loadUsers() {
            const search = document.getElementById('userSearchInput').value;
            const status = document.getElementById('userStatusFilter').value;
            try {
                const res = await api(`${API_BASE}/users?search=${encodeURIComponent(search)}&status=${encodeURIComponent(status)}`);
                const tbody = document.getElementById('usersTableBody');
                tbody.innerHTML = '';
                if (!res.data.length) {
                    tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">No users found.</td></tr>';
                    return;
                }
                res.data.forEach(u => {
                    const statusClass = `badge-${u.status}`;
                    const coins = u.wallet ? u.wallet.balance : 0;
                    const sub = u.active_subscription ? `<span class="badge badge-active">${u.active_subscription.plan ? u.active_subscription.plan.name : 'VIP'}</span>` : '<span style="color: var(--text-muted);">Free</span>';
                    tbody.innerHTML += `
                        <tr>
                            <td><strong>${u.name || 'Unnamed'}</strong><br><span style="font-size: 11px; color: var(--text-muted);">ID: #${u.id}</span></td>
                            <td>${u.email}</td>
                            <td><span class="badge ${statusClass}">${u.status}</span></td>
                            <td><strong>${coins}</strong> <i class="fa-solid fa-coins" style="color: var(--warning); font-size: 12px;"></i></td>
                            <td>${sub}</td>
                            <td style="font-size: 12px; color: var(--text-secondary);">${u.last_active_at ? new Date(u.last_active_at).toLocaleDateString() : 'Never'}</td>
                            <td>
                                <button class="btn btn-secondary btn-sm" onclick="openWalletModal(${u.id}, '${u.name}')" title="Adjust Coins"><i class="fa-solid fa-coins"></i></button>
                                <button class="btn btn-secondary btn-sm" onclick="openStatusModal(${u.id}, '${u.status}')" title="Change Status"><i class="fa-solid fa-user-gear"></i></button>
                            </td>
                        </tr>
                    `;
                });
            } catch (e) {}
        }

        // Reports
        async function loadReports() {
            const status = document.getElementById('reportStatusFilter').value;
            try {
                const res = await api(`${API_BASE}/reports?status=${encodeURIComponent(status)}`);
                const tbody = document.getElementById('reportsTableBody');
                tbody.innerHTML = '';
                if (!res.data.length) {
                    tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">No reports found.</td></tr>';
                    return;
                }
                res.data.forEach(r => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${r.reporter_name || 'User #' + r.reporter_id}</td>
                            <td><strong>${r.reported_name || 'User #' + r.reported_id}</strong></td>
                            <td>${r.reason}</td>
                            <td><span class="badge badge-${r.status}">${r.status}</span></td>
                            <td style="font-size: 12px;">${new Date(r.created_at).toLocaleDateString()}</td>
                            <td><button class="btn btn-primary btn-sm" onclick="openReportModal(${r.id})">Review</button></td>
                        </tr>
                    `;
                });
            } catch (e) {}
        }

        // Coin Packages
        async function loadPackages() {
            try {
                const res = await api(`${API_BASE}/coin-packages`);
                const tbody = document.getElementById('packagesTableBody');
                tbody.innerHTML = '';
                res.data.forEach(p => {
                    tbody.innerHTML += `
                        <tr>
                            <td><strong>${p.name}</strong></td>
                            <td>${p.coins}</td>
                            <td>+${p.bonus_coins}</td>
                            <td>$${p.price.toFixed(2)}</td>
                            <td><code>${p.google_product_id}</code></td>
                            <td><span class="badge badge-active">Active</span></td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="deletePackage(${p.id})"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
            } catch (e) {}
        }

        // Subscriptions
        async function loadSubscriptions() {
            try {
                const res = await api(`${API_BASE}/subscriptions`);
                const tbody = document.getElementById('subsTableBody');
                tbody.innerHTML = '';
                if (!res.data.length) {
                    tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">No active subscriptions.</td></tr>';
                    return;
                }
                res.data.forEach(s => {
                    tbody.innerHTML += `
                        <tr>
                            <td>User #${s.user_id}</td>
                            <td><span class="badge badge-active">${s.plan ? s.plan.name : 'Premium'}</span></td>
                            <td><span class="badge badge-${s.status}">${s.status}</span></td>
                            <td>${s.starts_at ? new Date(s.starts_at).toLocaleDateString() : '-'}</td>
                            <td>${s.ends_at ? new Date(s.ends_at).toLocaleDateString() : '-'}</td>
                        </tr>
                    `;
                });
            } catch (e) {}
        }

        // Settings
        async function loadSettings() {
            try {
                const res = await api(`${API_BASE}/settings`);
                res.data.forEach(s => {
                    if (s.key === 'call_coin_cost_per_minute') document.getElementById('setting_call_cost').value = s.value;
                    if (s.key === 'boost_coin_cost') document.getElementById('setting_boost_cost').value = s.value;
                    if (s.key === 'boost_duration_minutes') document.getElementById('setting_boost_duration').value = s.value;
                    if (s.key === 'super_like_coin_cost') document.getElementById('setting_super_like_cost').value = s.value;
                    if (s.key === 'free_daily_likes') document.getElementById('setting_free_likes').value = s.value;
                });
            } catch (e) {}
        }

        async function saveSettings(e) {
            e.preventDefault();
            const payload = {
                settings: [
                    { key: 'call_coin_cost_per_minute', value: document.getElementById('setting_call_cost').value },
                    { key: 'boost_coin_cost', value: document.getElementById('setting_boost_cost').value },
                    { key: 'boost_duration_minutes', value: document.getElementById('setting_boost_duration').value },
                    { key: 'super_like_coin_cost', value: document.getElementById('setting_super_like_cost').value },
                    { key: 'free_daily_likes', value: document.getElementById('setting_free_likes').value }
                ]
            };
            try {
                await api(`${API_BASE}/settings`, { method: 'PATCH', body: JSON.stringify(payload) });
                showToast('Settings saved successfully!');
            } catch (err) {
                showToast(err.message, true);
            }
        }

        // Modals & Handlers
        function openWalletModal(userId, name) {
            document.getElementById('adjustUserId').value = userId;
            document.getElementById('adjustUserName').value = name + ` (#${userId})`;
            document.getElementById('adjustAmount').value = '';
            document.getElementById('adjustReason').value = '';
            document.getElementById('walletModal').style.display = 'flex';
        }

        async function handleWalletAdjust(e) {
            e.preventDefault();
            const userId = document.getElementById('adjustUserId').value;
            const amount = parseInt(document.getElementById('adjustAmount').value);
            const reason = document.getElementById('adjustReason').value;

            try {
                await api(`${API_BASE}/users/${userId}/wallet/adjust`, {
                    method: 'POST',
                    body: JSON.stringify({ amount, reason })
                });
                closeModal('walletModal');
                showToast('Wallet balance adjusted successfully.');
                loadUsers();
            } catch (err) {
                showToast(err.message, true);
            }
        }

        function openStatusModal(userId, currentStatus) {
            document.getElementById('statusUserId').value = userId;
            document.getElementById('statusSelect').value = currentStatus;
            document.getElementById('statusReason').value = '';
            document.getElementById('statusModal').style.display = 'flex';
        }

        async function handleStatusUpdate(e) {
            e.preventDefault();
            const userId = document.getElementById('statusUserId').value;
            const status = document.getElementById('statusSelect').value;
            const reason = document.getElementById('statusReason').value;

            try {
                await api(`${API_BASE}/users/${userId}/status`, {
                    method: 'PATCH',
                    body: JSON.stringify({ status, reason })
                });
                closeModal('statusModal');
                showToast('User status updated.');
                loadUsers();
            } catch (err) {
                showToast(err.message, true);
            }
        }

        function openReportModal(reportId) {
            document.getElementById('reportId').value = reportId;
            document.getElementById('reportNotes').value = '';
            document.getElementById('reportModal').style.display = 'flex';
        }

        async function handleReportReview(e) {
            e.preventDefault();
            const reportId = document.getElementById('reportId').value;
            const status = document.getElementById('reportActionSelect').value;
            const resolution_notes = document.getElementById('reportNotes').value;

            try {
                await api(`${API_BASE}/reports/${reportId}`, {
                    method: 'PATCH',
                    body: JSON.stringify({ status, resolution_notes })
                });
                closeModal('reportModal');
                showToast('Report updated.');
                loadReports();
            } catch (err) {
                showToast(err.message, true);
            }
        }

        function openPackageModal() {
            document.getElementById('pkgId').value = '';
            document.getElementById('pkgName').value = '';
            document.getElementById('pkgCoins').value = '';
            document.getElementById('pkgBonus').value = '0';
            document.getElementById('pkgPrice').value = '';
            document.getElementById('pkgGoogleId').value = '';
            document.getElementById('packageModal').style.display = 'flex';
        }

        async function handlePackageSave(e) {
            e.preventDefault();
            const payload = {
                name: document.getElementById('pkgName').value,
                coins: parseInt(document.getElementById('pkgCoins').value),
                bonus_coins: parseInt(document.getElementById('pkgBonus').value) || 0,
                price: parseFloat(document.getElementById('pkgPrice').value),
                google_product_id: document.getElementById('pkgGoogleId').value,
                is_active: true
            };

            try {
                await api(`${API_BASE}/coin-packages`, {
                    method: 'POST',
                    body: JSON.stringify(payload)
                });
                closeModal('packageModal');
                showToast('Package created successfully.');
                loadPackages();
            } catch (err) {
                showToast(err.message, true);
            }
        }

        async function deletePackage(id) {
            if (!confirm('Are you sure you want to delete this coin package?')) return;
            try {
                await api(`${API_BASE}/coin-packages/${id}`, { method: 'DELETE' });
                showToast('Package deleted.');
                loadPackages();
            } catch (err) {
                showToast(err.message, true);
            }
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        // Initialize on Load
        window.onload = () => {
            initTheme();
            if (authToken) {
                document.getElementById('loginScreen').style.display = 'none';
                loadDashboard();
            }
        };
    </script>
</body>
</html>
