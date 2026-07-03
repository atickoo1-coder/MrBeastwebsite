<?php
/**
 * File: admin/auth/login.php
 * Purpose: Admin login page with authentication
 */

require_once __DIR__ . '/../../includes/config.php';

// If already logged in, redirect to dashboard
if (AuthController::isLoggedIn()) {
    redirect(ADMIN_PATH . '/dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    $result = $auth->login(
        $_POST['username'] ?? '',
        $_POST['password'] ?? '',
        $_POST['csrf_token'] ?? ''
    );

    if ($result['success']) {
        Session::setFlash('success', 'Welcome back, ' . Session::get('admin_username') . '!');
        redirect(ADMIN_PATH . '/dashboard.php');
    } else {
        $error = $result['message'];
    }
}

$csrfToken = generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #0d1117;
            --card-bg: #161b22;
            --border-color: #30363d;
            --text-primary: #e6edf3;
            --text-secondary: #8b949e;
            --accent-orange: #ff4d00;
            --accent-orange-hover: #e04400;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--primary-dark);
            color: var(--text-primary);
        }
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
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
            margin-bottom: 0.25rem;
        }
        .login-card .login-logo p {
            color: var(--text-secondary);
            font-size: 0.85rem;
            margin-bottom: 0;
        }
        .form-control {
            background: var(--primary-dark);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 6px;
            padding: 0.65rem 0.9rem;
        }
        .form-control:focus {
            background: var(--primary-dark);
            border-color: var(--accent-orange);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 77, 0, 0.15);
        }
        .form-control::placeholder { color: var(--text-secondary); }
        .form-label { font-size: 0.85rem; font-weight: 500; margin-bottom: 0.35rem; }
        .btn-orange {
            background: var(--accent-orange);
            border-color: var(--accent-orange);
            color: #fff;
            padding: 0.65rem 1rem;
            font-weight: 600;
        }
        .btn-orange:hover {
            background: var(--accent-orange-hover);
            border-color: var(--accent-orange-hover);
            color: #fff;
        }
        .input-group-text {
            background: var(--primary-dark);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
        }
        .alert-danger {
            background: rgba(248, 81, 73, 0.1);
            border: 1px solid rgba(248, 81, 73, 0.3);
            color: #f85149;
            border-radius: 6px;
            font-size: 0.85rem;
        }
        .store-link {
            display: block;
            text-align: center;
            margin-top: 1.25rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.85rem;
        }
        .store-link:hover { color: var(--accent-orange); }
        .form-check-label { color: var(--text-secondary); font-size: 0.85rem; }
        .form-check-input:checked { background-color: var(--accent-orange); border-color: var(--accent-orange); }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="login-card">
            <div class="login-logo">
                <div class="logo-icon">MB</div>
                <h1>MrBeast Store</h1>
                <p>Sign in to the admin panel</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <?php if (Session::hasFlash()): ?>
                <div class="mb-3"><?php Session::renderFlash(); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="username" name="username"
                               placeholder="Enter your username" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="Enter your password" required>
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="showPassword"
                           onclick="togglePassword()">
                    <label class="form-check-label" for="showPassword">Show password</label>
                </div>

                <button type="submit" class="btn btn-orange w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                </button>
            </form>

            <a href="<?= APP_URL ?>/index.html" class="store-link" target="_blank">
                <i class="bi bi-arrow-left me-1"></i> Back to Store
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function togglePassword() {
        const pwd = document.getElementById('password');
        pwd.type = pwd.type === 'password' ? 'text' : 'password';
    }
    </script>
</body>
</html>
