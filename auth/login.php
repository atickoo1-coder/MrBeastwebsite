<?php
require_once __DIR__ . '/../includes/frontend-config.php';
require_once __DIR__ . '/../includes/auth.php';

if (isUserLoggedIn()) {
    header('Location: ' . APP_URL . '/index.html');
    exit;
}

$pageTitle = 'Login';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | MrBeast Store</title>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🔥</text></svg>">
  <link rel="stylesheet" href="../css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    .auth-page { min-height: 100vh; display: flex; flex-direction: column; }
    .auth-container { flex: 1; display: flex; align-items: center; justify-content: center; padding: 60px 20px; }
    .auth-card { background: #111; border: 1px solid #222; border-radius: 16px; padding: 48px; width: 100%; max-width: 440px; }
    .auth-card h1 { font-family: 'Bebas Neue', sans-serif; font-size: 36px; letter-spacing: 2px; margin-bottom: 8px; text-align: center; }
    .auth-card .subtitle { text-align: center; color: #888; font-size: 14px; margin-bottom: 32px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 13px; font-weight: 600; color: #ccc; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 1px; }
    .form-group input { width: 100%; padding: 12px 16px; background: #1a1a1a; border: 1px solid #333; border-radius: 8px; color: #fff; font-size: 15px; font-family: 'Inter', sans-serif; transition: border-color 0.2s; box-sizing: border-box; }
    .form-group input:focus { outline: none; border-color: #ff4d00; }
    .btn-auth { width: 100%; padding: 14px; background: #ff4d00; color: #fff; border: none; border-radius: 8px; font-size: 16px; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer; transition: background 0.2s; text-transform: uppercase; letter-spacing: 1px; }
    .btn-auth:hover { background: #e04400; }
    .btn-auth:disabled { opacity: 0.6; cursor: not-allowed; }
    .auth-footer { text-align: center; margin-top: 24px; font-size: 14px; color: #888; }
    .auth-footer a { color: #ff4d00; text-decoration: none; }
    .auth-footer a:hover { text-decoration: underline; }
    .alert-msg { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; display: none; }
    .alert-error { background: rgba(255, 0, 0, 0.1); border: 1px solid rgba(255, 0, 0, 0.3); color: #ff4444; display: block; }
    .alert-success { background: rgba(0, 200, 0, 0.1); border: 1px solid rgba(0, 200, 0, 0.3); color: #00c853; display: block; }
    .password-toggle { position: relative; }
    .password-toggle input { padding-right: 44px; }
    .password-toggle .toggle-btn { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #666; cursor: pointer; font-size: 14px; padding: 4px; }
    .site-header { margin-bottom: 0; }
  </style>
</head>
<body>
<div class="auth-page">
  <header class="site-header">
    <div class="header-inner">
      <div class="header-left">
        <div class="mobile-toggle">&#9776;</div>
        <a href="../shop-all.html" class="nav-link">SHOP ALL</a>
      </div>
      <div class="header-center">
        <a href="../index.html" class="logo">MRBEAST.STORE</a>
      </div>
      <div class="header-right">
        <a href="../auth/register.php" class="icon-btn" style="text-decoration:none;font-size:14px;font-weight:600;color:#fff;">Sign Up</a>
        <button class="icon-btn cart-toggle">&#128722; <span class="cart-count">0</span></button>
      </div>
    </div>
  </header>

  <div class="auth-container">
    <div class="auth-card">
      <h1>WELCOME BACK</h1>
      <p class="subtitle">Log in to your MrBeast account</p>

      <div id="alert" class="alert-msg"></div>

      <form id="loginForm" novalidate>
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" placeholder="you@example.com" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <div class="password-toggle">
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <button type="button" class="toggle-btn" onclick="togglePassword()">Show</button>
          </div>
        </div>
        <button type="submit" class="btn-auth" id="submitBtn">Log In</button>
      </form>

      <div class="auth-footer">
        Don't have an account? <a href="register.php">Create one</a>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/../includes/footer.php'; ?>
</div>

<script>
function togglePassword() {
  const pwd = document.getElementById('password');
  const btn = event.target;
  if (pwd.type === 'password') {
    pwd.type = 'text';
    btn.textContent = 'Hide';
  } else {
    pwd.type = 'password';
    btn.textContent = 'Show';
  }
}

document.getElementById('loginForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn = document.getElementById('submitBtn');
  const alert = document.getElementById('alert');
  btn.disabled = true;
  btn.textContent = 'Logging in...';

  try {
    const res = await fetch('api.php?action=login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
      })
    });
    const data = await res.json();
    if (data.success) {
      window.location.href = '../index.html';
    } else {
      alert.className = 'alert-msg alert-error';
      alert.textContent = data.message;
      btn.disabled = false;
      btn.textContent = 'Log In';
    }
  } catch (err) {
    alert.className = 'alert-msg alert-error';
    alert.textContent = 'Connection error. Please try again.';
    btn.disabled = false;
    btn.textContent = 'Log In';
  }
});
</script>
</body>
</html>
