<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soul Connect — Master Admin Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            transition: background-color 0.15s ease, border-color 0.15s ease;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
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
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #ec4899, #8b5cf6);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .logo-text {
            font-weight: 700;
            font-size: 17px;
            letter-spacing: -0.5px;
        }

        .sidebar-menu {
            padding: 14px 10px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 3px;
            overflow-y: auto;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9px 12px;
            border-radius: 8px;
            color: var(--text-secondary);
            font-size: 13.5px;
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
            color: #ffffff !important;
        }

        .sidebar-footer {
            padding: 14px 16px;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Main Content */
        .main-wrapper {
            margin-left: 250px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            height: 64px;
            background: var(--bg-surface);
            border-bottom: 1px solid var(--border-color);
            padding: 0 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 30;
        }

        .header-title {
            font-size: 19px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-theme {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 7px 12px;
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
            padding: 7px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
        }

        .content {
            padding: 28px;
            flex: 1;
        }

        /* Stat Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--bg-surface);
            padding: 18px 20px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-label {
            font-size: 12.5px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .stat-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .stat-sub {
            font-size: 11.5px;
            color: var(--success);
            font-weight: 500;
        }

        /* Card Section */
        .card {
            background: var(--bg-surface);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .card-title {
            font-size: 15px;
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
            font-size: 13.5px;
        }

        th {
            background: var(--bg-primary);
            padding: 11px 16px;
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-color);
        }

        td {
            padding: 12px 16px;
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
            padding: 3px 8px;
            border-radius: 9999px;
            font-size: 11.5px;
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
            gap: 6px;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.15s;
        }

        .btn-primary { background: var(--primary); color: #ffffff; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-secondary { background: var(--bg-primary); border: 1px solid var(--border-color); color: var(--text-primary); }
        .btn-danger { background: var(--danger-light); color: var(--danger); border: 1px solid var(--danger); }
        .btn-sm { padding: 4px 9px; font-size: 12px; }

        .input-group {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            padding: 7px 12px;
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
            margin-bottom: 14px;
        }

        .form-group label {
            display: block;
            font-size: 12.5px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 5px;
        }

        .form-control {
            width: 100%;
            padding: 9px 12px;
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 13.5px;
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
            border-radius: 14px;
            width: 100%;
            max-width: 500px;
            border: 1px solid var(--border-color);
            box-shadow: var(--modal-shadow);
            overflow: hidden;
            animation: modalIn 0.15s ease-out;
        }

        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 14px 20px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
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
            padding: 36px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: var(--modal-shadow);
            width: 100%;
            max-width: 400px;
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            padding: 12px 18px;
            border-radius: 10px;
            color: #ffffff;
            font-size: 13.5px;
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
                    <h2 style="font-size: 19px; font-weight: 700;">Soul Connect</h2>
                    <p style="font-size: 12.5px; color: var(--text-secondary);">Master Administrator Portal</p>
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
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 11px; margin-top: 6px;">
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
            <a class="nav-item" onclick="switchTab('wallets')"><i class="fa-solid fa-receipt"></i> Financial Ledger</a>
            <a class="nav-item" onclick="switchTab('calls')"><i class="fa-solid fa-phone"></i> Call Records</a>
            <a class="nav-item" onclick="switchTab('reports')"><i class="fa-solid fa-shield-halved"></i> Abuse Reports</a>
            <a class="nav-item" onclick="switchTab('packages')"><i class="fa-solid fa-coins"></i> Coin Packages</a>
            <a class="nav-item" onclick="switchTab('gifts')"><i class="fa-solid fa-gift"></i> Gift Catalog</a>
            <a class="nav-item" onclick="switchTab('subscriptions')"><i class="fa-solid fa-gem"></i> Subscriptions</a>
            <a class="nav-item" onclick="switchTab('settings')"><i class="fa-solid fa-sliders"></i> System Settings</a>
        </nav>

        <div class="sidebar-footer">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold;">AD</div>
                <div style="font-size: 12.5px;">
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
                        <div class="stat-sub">Active pairs</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-label">Completed Calls</span>
                            <div class="stat-icon" style="background: var(--success-light); color: var(--success);"><i class="fa-solid fa-phone"></i></div>
                        </div>
                        <div class="stat-value" id="statCalls">-</div>
                        <div class="stat-sub"><span id="statCallMinutes">0</span> minutes billed</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-label">VIP Subscriptions</span>
                            <div class="stat-icon" style="background: var(--warning-light); color: var(--warning);"><i class="fa-solid fa-gem"></i></div>
                        </div>
                        <div class="stat-value" id="statSubs">-</div>
                        <div class="stat-sub">Active members</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <span class="stat-label">Coin Sales Revenue</span>
                            <div class="stat-icon" style="background: var(--success-light); color: var(--success);"><i class="fa-solid fa-dollar-sign"></i></div>
                        </div>
                        <div class="stat-value" id="statRevenue">$0.00</div>
                        <div class="stat-sub"><span id="statCirculatingCoins">0</span> coins in wallets</div>
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
                        <span class="card-title"><i class="fa-solid fa-circle-info" style="color: var(--primary); margin-right: 6px;"></i> Platform Status & Architecture</span>
                    </div>
                    <div style="padding: 20px;">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                            <div>
                                <span style="font-size: 12px; color: var(--text-secondary);">Backend Engine</span>
                                <div style="font-weight: 600; margin-top: 4px; color: var(--success);"><i class="fa-solid fa-check-circle"></i> Laravel 12 (PHP 8.4)</div>
                            </div>
                            <div>
                                <span style="font-size: 12px; color: var(--text-secondary);">Database Status</span>
                                <div style="font-weight: 600; margin-top: 4px; color: var(--success);"><i class="fa-solid fa-check-circle"></i> MySQL 8 ACID Ledger</div>
                            </div>
                            <div>
                                <span style="font-size: 12px; color: var(--text-secondary);">REST API Engine</span>
                                <div style="font-weight: 600; margin-top: 4px;"><code>/api/v1/</code> Active</div>
                            </div>
                            <div>
                                <span style="font-size: 12px; color: var(--text-secondary);">WebSockets Host</span>
                                <div style="font-weight: 600; margin-top: 4px;">Laravel Reverb (Port 8080)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- TAB 2: USERS -->
            <section id="tab-users" class="hidden">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">User Accounts & Balances</span>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <div class="input-group">
                                <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted);"></i>
                                <input type="text" id="userSearchInput" placeholder="Search name or email..." oninput="debounce(loadUsers, 300)()">
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
                                    <th>Wallet Balance</th>
                                    <th>VIP Tier</th>
                                    <th>Joined Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                <tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 25px;">Loading users...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- TAB 3: FINANCIAL LEDGER -->
            <section id="tab-wallets" class="hidden">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Live Coin Transactions & Audit Ledger</span>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Tx ID</th>
                                    <th>User</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Balance Before ➔ After</th>
                                    <th>Description</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody id="walletsTableBody">
                                <tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 25px;">Loading transaction ledger...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- TAB 4: CALL RECORDS -->
            <section id="tab-calls" class="hidden">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Voice & Video Call History</span>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Caller</th>
                                    <th>Receiver</th>
                                    <th>Type</th>
                                    <th>Duration</th>
                                    <th>Coins Charged</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="callsTableBody">
                                <tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 25px;">Loading call records...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- TAB 5: REPORTS -->
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
                                <tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 25px;">Loading reports...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- TAB 6: COIN PACKAGES -->
            <section id="tab-packages" class="hidden">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">In-App Coin Store Packages</span>
                        <button class="btn btn-primary btn-sm" onclick="openPackageModal()"><i class="fa-solid fa-plus"></i> Add Package</button>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Package Name</th>
                                    <th>Coins</th>
                                    <th>Bonus Coins</th>
                                    <th>Price (USD)</th>
                                    <th>Google Product SKU</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="packagesTableBody">
                                <tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 25px;">Loading packages...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- TAB 7: GIFT CATALOG -->
            <section id="tab-gifts" class="hidden">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Virtual Gift Catalog</span>
                        <button class="btn btn-primary btn-sm" onclick="openGiftModal()"><i class="fa-solid fa-plus"></i> Add Gift</button>
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
                            <tbody id="giftsTableBody">
                                <tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 25px;">Loading gifts...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- TAB 8: SUBSCRIPTIONS -->
            <section id="tab-subscriptions" class="hidden">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Active VIP Memberships</span>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Plan</th>
                                    <th>Order ID</th>
                                    <th>Starts At</th>
                                    <th>Ends At</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="subsTableBody">
                                <tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 25px;">Loading subscriptions...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- TAB 9: SETTINGS -->
            <section id="tab-settings" class="hidden">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Application Dynamic Pricing & Limits</span>
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
                                <label>Daily Free Swipes (Free accounts)</label>
                                <input type="number" id="setting_free_likes" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Save Configuration</button>
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
                <h3 style="font-size: 15px; font-weight: 600;">Adjust User Coins</h3>
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
                        <label>Coin Adjustment (+ to add, - to deduct)</label>
                        <input type="number" id="adjustAmount" class="form-control" placeholder="e.g. 100 or -50" required>
                    </div>
                    <div class="form-group">
                        <label>Mandatory Audit Reason</label>
                        <input type="text" id="adjustReason" class="form-control" placeholder="e.g. Customer support compensation" required minlength="5">
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
                <h3 style="font-size: 15px; font-weight: 600;">Update Account Status</h3>
                <button onclick="closeModal('statusModal')" style="background: none; border: none; font-size: 18px; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>
            <form onsubmit="handleStatusUpdate(event)">
                <div class="modal-body">
                    <input type="hidden" id="statusUserId">
                    <div class="form-group">
                        <label>Account Status</label>
                        <select id="statusSelect" class="form-control">
                            <option value="active">Active (Full access)</option>
                            <option value="suspended">Suspended (Temporary hold)</option>
                            <option value="banned">Banned (Permanent block)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Reason for Action</label>
                        <input type="text" id="statusReason" class="form-control" placeholder="e.g. Terms violation / spam">
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
                <h3 style="font-size: 15px; font-weight: 600;">Review Abuse Report</h3>
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
                <h3 style="font-size: 15px; font-weight: 600;" id="pkgModalTitle">Add Coin Package</h3>
                <button onclick="closeModal('packageModal')" style="background: none; border: none; font-size: 18px; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>
            <form onsubmit="handlePackageSave(event)">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Package Name</label>
                        <input type="text" id="pkgName" class="form-control" placeholder="e.g. Popular Pack" required>
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
                            <label>Google Product SKU</label>
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

    <!-- MODAL: ADD VIRTUAL GIFT -->
    <div id="giftModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h3 style="font-size: 15px; font-weight: 600;">Add Virtual Gift</h3>
                <button onclick="closeModal('giftModal')" style="background: none; border: none; font-size: 18px; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>
            <form onsubmit="handleGiftSave(event)">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Gift Name</label>
                        <input type="text" id="giftName" class="form-control" placeholder="e.g. Red Rose" required>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group">
                            <label>Emoji / Icon</label>
                            <input type="text" id="giftIcon" class="form-control" placeholder="🌹" required>
                        </div>
                        <div class="form-group">
                            <label>Coin Cost</label>
                            <input type="number" id="giftPrice" class="form-control" placeholder="25" required min="1">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select id="giftCategory" class="form-control">
                            <option value="romance">Romance</option>
                            <option value="fun">Fun</option>
                            <option value="luxury">Luxury</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('giftModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Gift</button>
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
            setTimeout(() => { t.style.display = 'none'; }, 3200);
        }

        // Debounce helper for instant smooth searching
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        // Fetch Wrapper with Token & X-Admin-Token
        async function api(url, options = {}) {
            options.headers = options.headers || {};
            options.headers['Accept'] = 'application/json';
            options.headers['Content-Type'] = 'application/json';
            if (authToken) {
                options.headers['Authorization'] = `Bearer ${authToken}`;
                options.headers['X-Admin-Token'] = authToken;
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

            ['dashboard', 'users', 'wallets', 'calls', 'reports', 'packages', 'gifts', 'subscriptions', 'settings'].forEach(t => {
                document.getElementById(`tab-${t}`)?.classList.add('hidden');
            });
            document.getElementById(`tab-${tabId}`)?.classList.remove('hidden');

            const titles = {
                dashboard: 'Dashboard Overview',
                users: 'User Accounts & Balances',
                wallets: 'Financial Transactions Ledger',
                calls: 'Voice & Video Call History',
                reports: 'Abuse & Safety Reports',
                packages: 'In-App Coin Packages',
                gifts: 'Virtual Gift Catalog',
                subscriptions: 'VIP Memberships',
                settings: 'System Pricing & Limits'
            };
            document.getElementById('pageTitle').innerText = titles[tabId] || 'Admin Portal';

            if (tabId === 'dashboard') loadDashboard();
            if (tabId === 'users') loadUsers();
            if (tabId === 'wallets') loadWallets();
            if (tabId === 'calls') loadCalls();
            if (tabId === 'reports') loadReports();
            if (tabId === 'packages') loadPackages();
            if (tabId === 'gifts') loadGifts();
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
                document.getElementById('statCallMinutes').innerText = d.calls.total_minutes || 0;
                document.getElementById('statSubs').innerText = d.subscriptions.active;
                document.getElementById('statRevenue').innerText = '$' + (d.finance?.total_coin_sales_usd || 0).toFixed(2);
                document.getElementById('statCirculatingCoins').innerText = d.finance?.total_coins_in_wallets || 0;
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
                const users = res.data?.data || res.data || [];
                if (!users.length) {
                    tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 25px;">No users found.</td></tr>';
                    return;
                }
                users.forEach(u => {
                    const statusClass = `badge-${u.status}`;
                    const coins = u.wallet ? u.wallet.balance : 0;
                    const sub = u.active_subscription ? `<span class="badge badge-active">${u.active_subscription.plan ? u.active_subscription.plan.name : 'VIP'}</span>` : '<span style="color: var(--text-muted);">Free</span>';
                    const displayName = u.name || u.email.split('@')[0];
                    tbody.innerHTML += `
                        <tr>
                            <td><strong>${displayName}</strong><br><span style="font-size: 11px; color: var(--text-muted);">ID: #${u.id}</span></td>
                            <td>${u.email}</td>
                            <td><span class="badge ${statusClass}">${u.status}</span></td>
                            <td><strong>${coins}</strong> <i class="fa-solid fa-coins" style="color: var(--warning); font-size: 12px;"></i></td>
                            <td>${sub}</td>
                            <td style="font-size: 12px; color: var(--text-secondary);">${new Date(u.created_at).toLocaleDateString()}</td>
                            <td>
                                <button class="btn btn-secondary btn-sm" onclick="openWalletModal(${u.id}, '${displayName}')" title="Adjust Coins"><i class="fa-solid fa-coins"></i></button>
                                <button class="btn btn-secondary btn-sm" onclick="openStatusModal(${u.id}, '${u.status}')" title="Change Status"><i class="fa-solid fa-user-gear"></i></button>
                            </td>
                        </tr>
                    `;
                });
            } catch (e) {}
        }

        // Financial Ledger
        async function loadWallets() {
            try {
                const res = await api(`${API_BASE}/wallet-transactions`);
                const tbody = document.getElementById('walletsTableBody');
                tbody.innerHTML = '';
                const txs = res.data?.data || res.data || [];
                if (!txs.length) {
                    tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 25px;">No wallet transactions recorded.</td></tr>';
                    return;
                }
                txs.forEach(t => {
                    const isCredit = t.amount > 0;
                    const amountBadge = isCredit ? `<span style="color: var(--success); font-weight: bold;">+${t.amount} 🪙</span>` : `<span style="color: var(--danger); font-weight: bold;">${t.amount} 🪙</span>`;
                    tbody.innerHTML += `
                        <tr>
                            <td><code>#${t.id}</code></td>
                            <td>User #${t.user_id}</td>
                            <td><span class="badge badge-active">${t.type}</span></td>
                            <td>${amountBadge}</td>
                            <td>${t.balance_before} ➔ <strong>${t.balance_after}</strong></td>
                            <td style="font-size: 12.5px;">${t.description || '-'}</td>
                            <td style="font-size: 12px; color: var(--text-secondary);">${new Date(t.created_at).toLocaleString()}</td>
                        </tr>
                    `;
                });
            } catch (e) {}
        }

        // Call Records
        async function loadCalls() {
            try {
                const res = await api(`${API_BASE}/calls`);
                const tbody = document.getElementById('callsTableBody');
                tbody.innerHTML = '';
                const calls = res.data || [];
                if (!calls.length) {
                    tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 25px;">No calls recorded yet.</td></tr>';
                    return;
                }
                calls.forEach(c => {
                    const durationMins = Math.ceil((c.duration_seconds || 0) / 60);
                    tbody.innerHTML += `
                        <tr>
                            <td>User #${c.caller_id}</td>
                            <td>User #${c.receiver_id}</td>
                            <td><span class="badge badge-active">${c.type}</span></td>
                            <td>${c.duration_seconds || 0}s (${durationMins}m)</td>
                            <td><strong>${c.total_cost || 0}</strong> 🪙</td>
                            <td><span class="badge badge-${c.status === 'ended' ? 'resolved' : 'pending'}">${c.status}</span></td>
                            <td style="font-size: 12px;">${new Date(c.created_at).toLocaleString()}</td>
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
                const reports = res.data?.data || res.data || [];
                if (!reports.length) {
                    tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 25px;">No reports found.</td></tr>';
                    return;
                }
                reports.forEach(r => {
                    tbody.innerHTML += `
                        <tr>
                            <td>User #${r.reporter_id}</td>
                            <td><strong>User #${r.reported_id}</strong></td>
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

        // Virtual Gifts
        async function loadGifts() {
            try {
                const res = await api(`${API_BASE}/gifts`);
                const tbody = document.getElementById('giftsTableBody');
                tbody.innerHTML = '';
                res.data.forEach(g => {
                    tbody.innerHTML += `
                        <tr>
                            <td style="font-size: 22px;">${g.icon_url}</td>
                            <td><strong>${g.name}</strong></td>
                            <td>${g.coin_price} 🪙</td>
                            <td><span class="badge badge-active">${g.category || 'Standard'}</span></td>
                            <td><span class="badge badge-active">Active</span></td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="deleteGift(${g.id})"><i class="fa-solid fa-trash"></i></button>
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
                const subs = res.data?.data || res.data || [];
                if (!subs.length) {
                    tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 25px;">No active subscriptions.</td></tr>';
                    return;
                }
                subs.forEach(s => {
                    tbody.innerHTML += `
                        <tr>
                            <td>User #${s.user_id}</td>
                            <td><span class="badge badge-active">${s.plan ? s.plan.name : 'VIP Gold'}</span></td>
                            <td><code>${s.order_id || 'Direct'}</code></td>
                            <td>${s.starts_at ? new Date(s.starts_at).toLocaleDateString() : '-'}</td>
                            <td>${s.ends_at ? new Date(s.ends_at).toLocaleDateString() : '-'}</td>
                            <td><span class="badge badge-${s.status}">${s.status}</span></td>
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
                loadDashboard();
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

        function openGiftModal() {
            document.getElementById('giftName').value = '';
            document.getElementById('giftIcon').value = '🌹';
            document.getElementById('giftPrice').value = '25';
            document.getElementById('giftModal').style.display = 'flex';
        }

        async function handleGiftSave(e) {
            e.preventDefault();
            const payload = {
                name: document.getElementById('giftName').value,
                icon_url: document.getElementById('giftIcon').value,
                coin_price: parseInt(document.getElementById('giftPrice').value),
                category: document.getElementById('giftCategory').value,
                is_active: true
            };

            try {
                await api(`${API_BASE}/gifts`, {
                    method: 'POST',
                    body: JSON.stringify(payload)
                });
                closeModal('giftModal');
                showToast('Virtual gift created.');
                loadGifts();
            } catch (err) {
                showToast(err.message, true);
            }
        }

        async function deleteGift(id) {
            if (!confirm('Are you sure you want to delete this gift?')) return;
            try {
                await api(`${API_BASE}/gifts/${id}`, { method: 'DELETE' });
                showToast('Gift deleted.');
                loadGifts();
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
