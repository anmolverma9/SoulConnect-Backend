<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal') — Soul Connect</title>
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
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
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
        .badge-reviewing { background: var(--primary-light); color: var(--primary); }
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
            text-decoration: none;
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

        /* Alerts */
        .alert {
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success { background: var(--success-light); color: var(--success); border: 1px solid var(--success); }
        /* Complete Pagination Styling */
        .pagination-container, .pagination {
            display: flex;
            padding: 16px 20px;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--border-color);
            flex-wrap: wrap;
            gap: 12px;
            font-size: 13px;
        }

        nav[role="navigation"] {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
        }

        nav[role="navigation"] svg {
            width: 14px !important;
            height: 14px !important;
            max-width: 14px !important;
            max-height: 14px !important;
            display: inline-block !important;
            vertical-align: middle;
        }

        nav[role="navigation"] > div:first-child {
            display: none; /* Hide default raw Tailwind text */
        }

        nav[role="navigation"] span, 
        nav[role="navigation"] a {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            padding: 6px 12px !important;
            border-radius: 8px !important;
            border: 1px solid var(--border-color) !important;
            background: var(--bg-surface) !important;
            color: var(--text-primary) !important;
            text-decoration: none !important;
            font-size: 12.5px !important;
            font-weight: 500 !important;
            min-height: 32px;
            box-shadow: none !important;
        }

        nav[role="navigation"] span[aria-current="page"] > span,
        nav[role="navigation"] span[aria-current="page"],
        nav[role="navigation"] .active span {
            background: var(--primary) !important;
            color: #ffffff !important;
            border-color: var(--primary) !important;
            font-weight: 700 !important;
        }

        nav[role="navigation"] a:hover {
            background: var(--primary-light) !important;
            color: var(--primary) !important;
            border-color: var(--primary) !important;
        }

        nav[role="navigation"] span[aria-disabled="true"] {
            opacity: 0.45;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo-icon"><i class="fa-solid fa-heart"></i></div>
            <span class="logo-text">Soul Connect</span>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>
            <a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i> Users & Profiles
            </a>
            <a href="{{ route('admin.wallets') }}" class="nav-item {{ request()->routeIs('admin.wallets*') ? 'active' : '' }}">
                <i class="fa-solid fa-receipt"></i> Financial Ledger
            </a>
            <a href="{{ route('admin.calls') }}" class="nav-item {{ request()->routeIs('admin.calls*') ? 'active' : '' }}">
                <i class="fa-solid fa-phone"></i> Call Records
            </a>
            <a href="{{ route('admin.reports') }}" class="nav-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                <i class="fa-solid fa-shield-halved"></i> Abuse Reports
            </a>
            <a href="{{ route('admin.packages') }}" class="nav-item {{ request()->routeIs('admin.packages*') ? 'active' : '' }}">
                <i class="fa-solid fa-coins"></i> Coin Packages
            </a>
            <a href="{{ route('admin.gifts') }}" class="nav-item {{ request()->routeIs('admin.gifts*') ? 'active' : '' }}">
                <i class="fa-solid fa-gift"></i> Gift Catalog
            </a>
            <a href="{{ route('admin.subscriptions') }}" class="nav-item {{ request()->routeIs('admin.subscriptions*') ? 'active' : '' }}">
                <i class="fa-solid fa-gem"></i> Subscriptions
            </a>
            <a href="{{ route('admin.bot_messages') }}" class="nav-item {{ request()->routeIs('admin.bot_messages*') ? 'active' : '' }}">
                <i class="fa-solid fa-robot"></i> Bot Messages Bank
            </a>
            <a href="{{ route('admin.conversations') }}" class="nav-item {{ request()->routeIs('admin.conversations*') ? 'active' : '' }}">
                <i class="fa-solid fa-comments"></i> Live Chat Monitor
            </a>
            <a href="{{ route('admin.settings') }}" class="nav-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                <i class="fa-solid fa-sliders"></i> System Settings
            </a>
        </nav>

        <div class="sidebar-footer">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold;">
                    {{ substr(Auth::guard('admin_web')->user()?->name ?? 'Admin', 0, 2) }}
                </div>
                <div style="font-size: 12.5px;">
                    <div style="font-weight: 600;">{{ Auth::guard('admin_web')->user()?->name ?? 'Admin' }}</div>
                    <div style="font-size: 11px; color: var(--text-muted);">{{ Auth::guard('admin_web')->user()?->role ?? 'Super Admin' }}</div>
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
            <h1 class="header-title">@yield('page_title', 'Dashboard')</h1>
            <div class="header-actions">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout"><i class="fa-solid fa-power-off"></i> Logout</button>
                </form>
            </div>
        </header>

        <main class="content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @yield('modals')

    <script>
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

        function openModal(id) {
            document.getElementById(id).style.display = 'flex';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        window.onload = initTheme;
    </script>
</body>
</html>
