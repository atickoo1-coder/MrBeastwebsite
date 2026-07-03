<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Dashboard') ?> - <?= APP_NAME ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Admin Custom Styles -->
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-dark: #0d1117;
            --sidebar-bg: #161b22;
            --card-bg: #21262d;
            --border-color: #30363d;
            --text-primary: #e6edf3;
            --text-secondary: #8b949e;
            --accent-orange: #ff4d00;
            --accent-orange-hover: #e04400;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--primary-dark);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-brand .brand-icon {
            width: 36px;
            height: 36px;
            background: var(--accent-orange);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.1rem;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar-brand .brand-text {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .sidebar-brand .brand-sub {
            font-size: 0.7rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .sidebar-nav .nav-section {
            padding: 0.5rem 1.5rem 0.25rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .sidebar-nav .nav-item {
            list-style: none;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-nav .nav-link:hover {
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-nav .nav-link.active {
            color: var(--accent-orange);
            background: rgba(255, 77, 0, 0.1);
            border-left-color: var(--accent-orange);
        }

        .sidebar-nav .nav-link i {
            font-size: 1.15rem;
            width: 20px;
            text-align: center;
        }

        /* Main content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Top navbar */
        .topbar {
            background: var(--sidebar-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 1.3rem;
            cursor: pointer;
            padding: 0.25rem;
            display: none;
        }

        .sidebar-toggle:hover {
            color: var(--text-primary);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        .topbar-user .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--accent-orange);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.85rem;
        }

        /* Page content wrapper */
        .page-content {
            padding: 1.5rem;
        }

        /* Cards */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.25rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.25rem;
        }

        /* Stats cards */
        .stat-card {
            padding: 1.25rem;
            border-radius: 8px;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 0.75rem;
        }

        .stat-card .stat-icon.orange {
            background: rgba(255, 77, 0, 0.15);
            color: var(--accent-orange);
        }

        .stat-card .stat-icon.green {
            background: rgba(46, 213, 115, 0.15);
            color: #2ed573;
        }

        .stat-card .stat-icon.blue {
            background: rgba(54, 164, 255, 0.15);
            color: #36a4ff;
        }

        .stat-card .stat-icon.purple {
            background: rgba(130, 87, 229, 0.15);
            color: #8257e5;
        }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stat-card .stat-label {
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        /* Tables */
        .table {
            color: var(--text-primary);
            margin-bottom: 0;
        }

        .table > :not(caption) > * > * {
            border-bottom-color: var(--border-color);
            color: var(--text-primary);
        }

        .table > thead {
            border-bottom: 2px solid var(--border-color);
        }

        .table > thead > tr > th {
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            border-bottom: none;
            padding: 0.75rem;
        }

        .table > tbody > tr > td {
            padding: 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }

        .table > tbody > tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        /* Forms */
        .form-control, .form-select {
            background: var(--primary-dark);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 6px;
        }

        .form-control:focus, .form-select:focus {
            background: var(--primary-dark);
            border-color: var(--accent-orange);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 77, 0, 0.15);
        }

        .form-control::placeholder {
            color: var(--text-secondary);
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.35rem;
        }

        /* Buttons */
        .btn-orange {
            background: var(--accent-orange);
            border-color: var(--accent-orange);
            color: #fff;
        }

        .btn-orange:hover {
            background: var(--accent-orange-hover);
            border-color: var(--accent-orange-hover);
            color: #fff;
        }

        .btn-outline-orange {
            border-color: var(--accent-orange);
            color: var(--accent-orange);
        }

        .btn-outline-orange:hover {
            background: var(--accent-orange);
            color: #fff;
        }

        /* Pagination */
        .pagination .page-link {
            background: var(--card-bg);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .pagination .page-link:hover {
            background: rgba(255, 77, 0, 0.15);
            border-color: var(--accent-orange);
            color: var(--accent-orange);
        }

        .pagination .page-item.active .page-link {
            background: var(--accent-orange);
            border-color: var(--accent-orange);
        }

        .pagination .page-item.disabled .page-link {
            background: var(--card-bg);
            color: var(--text-secondary);
            opacity: 0.5;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 6px;
        }

        /* DataTables overrides */
        .dataTables_wrapper .dataTables_filter input {
            background: var(--primary-dark);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 6px;
        }

        .dataTables_wrapper .dataTables_length select {
            background: var(--primary-dark);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 0;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: var(--text-secondary);
        }

        .breadcrumb-item a {
            color: var(--text-secondary);
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            color: var(--accent-orange);
        }

        .breadcrumb-item.active {
            color: var(--text-primary);
        }

        /* Product image in table */
        .product-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            background: var(--primary-dark);
        }

        .product-thumb-placeholder {
            width: 50px;
            height: 50px;
            border-radius: 6px;
            background: var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            font-size: 1.2rem;
        }

        /* Image preview */
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--primary-dark);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }

        /* Login page */
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-dark);
            padding: 1rem;
        }

        .login-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
        }

        .login-card .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-card .login-logo .logo-icon {
            width: 56px;
            height: 56px;
            background: var(--accent-orange);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.5rem;
            color: #fff;
            margin: 0 auto 0.75rem;
        }

        .login-card .login-logo h1 {
            font-size: 1.25rem;
            font-weight: 700;
        }

        .login-card .login-logo p {
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }

            .stat-card .stat-value {
                font-size: 1.35rem;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay d-md-none" id="sidebarOverlay"
         style="position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:999;display:none;"
         onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">MB</div>
            <div>
                <div class="brand-text">MrBeast Store</div>
                <div class="brand-sub">Admin Panel</div>
            </div>
        </div>

        <ul class="sidebar-nav">
            <li class="nav-section">Main</li>
            <li class="nav-item">
                <a href="<?= ADMIN_PATH ?>/dashboard.php" class="nav-link <?= isActive('dashboard.php') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>

            <li class="nav-section">Management</li>
            <li class="nav-item">
                <a href="<?= ADMIN_PATH ?>/products/index.php" class="nav-link <?= isActive('products/index.php') || isActive('products/create.php') || isActive('products/edit.php') ? 'active' : '' ?>">
                    <i class="bi bi-box-seam"></i> Products
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= ADMIN_PATH ?>/categories/index.php" class="nav-link <?= isActive('categories/index.php') || isActive('categories/create.php') || isActive('categories/edit.php') ? 'active' : '' ?>">
                    <i class="bi bi-grid"></i> Categories
                </a>
            </li>

            <li class="nav-section">System</li>
            <li class="nav-item">
                <a href="<?= ADMIN_PATH ?>/auth/logout.php" class="nav-link">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main content -->
    <div class="main-content">

        <!-- Top navbar -->
        <nav class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <div class="d-none d-md-block">
                    <?= $breadcrumbHtml ?? '' ?>
                </div>
            </div>
            <div class="topbar-right">
                <div class="topbar-user">
                    <span class="d-none d-sm-inline"><?= e(Session::get('admin_username')) ?></span>
                    <div class="avatar"><?= strtoupper(substr(Session::get('admin_username'), 0, 1)) ?></div>
                </div>
            </div>
        </nav>

        <!-- Page content -->
        <div class="page-content">

            <!-- Mobile breadcrumb -->
            <div class="d-md-none mb-3">
                <?= $breadcrumbHtml ?? '' ?>
            </div>

            <!-- Flash messages -->
            <?php if (Session::hasFlash()): ?>
                <div class="mb-3">
                    <?php Session::renderFlash(); ?>
                </div>
            <?php endif; ?>
