<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
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
            --sidebar-width: 250px;
            --header-height: 64px;
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
            position: relative;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-header {
            height: var(--header-height);
            padding: 0 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
        }

        .logo-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: inherit;
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
            flex-shrink: 0;
        }

        .logo-text {
            font-weight: 700;
            font-size: 17px;
            letter-spacing: -0.5px;
            white-space: nowrap;
        }

        .sidebar-close-btn {
            display: none;
            background: transparent;
            border: none;
            font-size: 18px;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
        }

        .sidebar-close-btn:hover {
            color: var(--text-primary);
            background: var(--primary-light);
        }

        .sidebar-menu {
            padding: 12px 10px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 3px;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
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
            white-space: nowrap;
        }

        .nav-item i {
            width: 18px;
            text-align: center;
            font-size: 14px;
            flex-shrink: 0;
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
            padding: 12px 14px;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            background: var(--bg-sidebar);
        }

        /* Sidebar Backdrop on Mobile */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(2px);
            z-index: 90;
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        /* Main Content */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
            width: calc(100% - var(--sidebar-width));
            transition: margin-left 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        header {
            height: var(--header-height);
            background: var(--bg-surface);
            border-bottom: 1px solid var(--border-color);
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
            gap: 12px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .mobile-toggle {
            display: none;
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            width: 38px;
            height: 38px;
            border-radius: 8px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .mobile-toggle:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .header-title {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .btn-theme {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 7px 11px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 13px;
            height: 36px;
        }

        .btn-logout {
            background: var(--danger-light);
            color: var(--danger);
            border: none;
            padding: 7px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            height: 36px;
            white-space: nowrap;
        }

        .btn-logout:hover {
            opacity: 0.9;
        }

        .content {
            padding: 24px;
            flex: 1;
            min-width: 0;
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
            min-width: 0;
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .stat-label, .stat-title {
            font-size: 12.5px;
            color: var(--text-secondary);
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stat-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.5px;
        }

        .stat-sub {
            font-size: 11.5px;
            color: var(--success);
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Responsive Table */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            min-width: 600px;
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
            white-space: nowrap;
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
            white-space: nowrap;
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
            white-space: nowrap;
        }

        .btn-primary { background: var(--primary); color: #ffffff; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-secondary { background: var(--bg-primary); border: 1px solid var(--border-color); color: var(--text-primary); }
        .btn-danger { background: var(--danger-light); color: var(--danger); border: 1px solid var(--danger); }
        .btn-sm { padding: 5px 10px; font-size: 12px; }

        .input-group {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            padding: 7px 12px;
            border-radius: 8px;
            min-width: 0;
        }

        .input-group input, .input-group select {
            background: transparent;
            border: none;
            outline: none;
            color: var(--text-primary);
            font-size: 13px;
            width: 100%;
            min-width: 0;
        }

        .form-group {
            margin-bottom: 14px;
        }

        .form-group label, .form-label {
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
        .modal-overlay, .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 16px;
            overflow-y: auto;
        }

        .modal {
            background: var(--bg-surface);
            border-radius: 14px;
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            border: 1px solid var(--border-color);
            box-shadow: var(--modal-shadow);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: modalIn 0.15s ease-out;
            margin: auto;
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
            flex-shrink: 0;
        }

        .modal-body {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
        }

        .modal-footer {
            padding: 14px 20px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            background: var(--bg-primary);
            flex-shrink: 0;
            flex-wrap: wrap;
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: var(--text-muted);
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-close:hover {
            color: var(--text-primary);
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
        .alert-danger { background: var(--danger-light); color: var(--danger); border: 1px solid var(--danger); }

        /* Complete Pagination Styling */
        .custom-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            background: var(--bg-surface);
            border-top: 1px solid var(--border-color);
            flex-wrap: wrap;
            gap: 12px;
        }

        .pagination-info {
            font-size: 13px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .pagination-info .highlight {
            font-weight: 700;
            color: var(--text-primary);
        }

        .pagination-pages {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
        }

        .page-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text-primary);
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }

        .page-btn:hover:not(.disabled):not(.active) {
            background: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary);
            transform: translateY(-1px);
        }

        .page-btn.active {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #ffffff;
            border-color: transparent;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.35);
            cursor: default;
        }

        .page-btn.disabled {
            color: var(--text-muted);
            background: var(--bg-primary);
            border-color: var(--border-color);
            opacity: 0.6;
            cursor: not-allowed;
            box-shadow: none;
        }

        /* Generic Responsive Utilities */
        .responsive-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .responsive-flex-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        /* ----------------------------------------------------
           RESPONSIVE BREAKPOINTS
           ---------------------------------------------------- */

        /* Tablets & Smaller (< 992px) */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                box-shadow: var(--modal-shadow);
            }

            body.sidebar-open .sidebar {
                transform: translateX(0);
            }

            body.sidebar-open .sidebar-backdrop {
                display: block;
                opacity: 1;
            }

            .sidebar-close-btn {
                display: block;
            }

            .main-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .mobile-toggle {
                display: inline-flex;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Mobile Devices (< 768px) */
        @media (max-width: 768px) {
            :root {
                --header-height: 56px;
            }

            header {
                padding: 0 14px;
            }

            .header-title {
                font-size: 16px;
            }

            .content {
                padding: 16px 12px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
                margin-bottom: 18px;
            }

            .stat-card {
                padding: 14px 16px;
            }

            .stat-value {
                font-size: 20px;
            }

            .card-header {
                padding: 12px 14px;
            }

            .card-title {
                font-size: 14px;
            }

            th, td {
                padding: 10px 12px;
            }

            .custom-pagination {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 12px 14px;
                gap: 10px;
            }

            .pagination-pages {
                justify-content: center;
            }

            .responsive-grid-2 {
                grid-template-columns: 1fr !important;
            }

            .btn-logout span {
                display: none;
            }
        }

        /* Small Phones (< 480px) */
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .header-actions .btn-theme {
                padding: 6px 9px;
            }

            .btn {
                font-size: 12px;
                padding: 6px 10px;
            }

            .page-btn {
                min-width: 30px;
                height: 30px;
                padding: 0 8px;
                font-size: 11.5px;
            }

            .modal {
                border-radius: 10px;
            }

            .modal-header, .modal-body, .modal-footer {
                padding: 12px 14px;
            }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR BACKDROP FOR MOBILE -->
    <div id="sidebarBackdrop" class="sidebar-backdrop" onclick="toggleSidebar()"></div>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="logo-brand">
                <div class="logo-icon"><i class="fa-solid fa-heart"></i></div>
                <span class="logo-text">Soul Connect</span>
            </a>
            <button class="sidebar-close-btn" onclick="toggleSidebar()" title="Close Menu">
                <i class="fa-solid fa-xmark"></i>
            </button>
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
            <div style="display: flex; align-items: center; gap: 10px; min-width: 0;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; flex-shrink: 0;">
                    {{ substr(Auth::guard('admin_web')->user()?->name ?? 'Admin', 0, 2) }}
                </div>
                <div style="font-size: 12.5px; min-width: 0;">
                    <div style="font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ Auth::guard('admin_web')->user()?->name ?? 'Admin' }}</div>
                    <div style="font-size: 11px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ Auth::guard('admin_web')->user()?->role ?? 'Super Admin' }}</div>
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
            <div class="header-left">
                <button class="mobile-toggle" onclick="toggleSidebar()" title="Toggle Navigation Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="header-title">@yield('page_title', 'Dashboard')</h1>
            </div>
            <div class="header-actions">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout" title="Sign Out">
                        <i class="fa-solid fa-power-off"></i> <span>Logout</span>
                    </button>
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
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-open');
        }

        function closeSidebar() {
            document.body.classList.remove('sidebar-open');
        }

        // Close sidebar on link click when on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.sidebar-menu .nav-item');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 992) {
                        closeSidebar();
                    }
                });
            });
        });

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
            const el = document.getElementById(id);
            if (el) el.style.display = 'flex';
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            if (el) el.style.display = 'none';
        }

        // Close modals on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay, .modal-backdrop').forEach(modal => {
                    modal.style.display = 'none';
                });
                closeSidebar();
            }
        });

        window.onload = initTheme;
    </script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
