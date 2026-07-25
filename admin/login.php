<?php
require_once __DIR__ . '/../includes/config.php';

if (isset($_SESSION['admin_id'])) {
    redirect('dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        try {
            require_once __DIR__ . '/../includes/database.php';
            $db = Database::getInstance();
            $user = $db->fetch('SELECT * FROM admin_users WHERE username = ?', [$username]);

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_user'] = $user['username'];
                redirect('dashboard.php');
            } else {
                $error = 'Invalid credentials.';
            }
        } catch (\Exception $e) {
            $error = 'System error. Please contact support.';
        }
    } else {
        $error = 'Please enter username and password.';
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?= escape(SITE_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, sans-serif; background: #f0f2f5; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
        .login-card { background: #fff; border-radius: 12px; padding: 40px; width: 100%; max-width: 400px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); text-align: center; }
        .login-card .admin-logo { max-width: 240px; height: auto; margin-bottom: 1.5rem; }
        .login-card h1 { font-size: 1.5rem; color: #77797d; text-align: center; margin-bottom: 2rem; }
        .pw-wrap { position: relative; }
        .pw-wrap input { padding-right: 48px !important; }
        .pw-toggle { position: absolute; top: 50%; right: 12px; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; font-size: 1.1rem; color: #77797d; line-height: 1; }
        .form-group { margin-bottom: 1.25rem; text-align: left; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 500; color: #233d7e; margin-bottom: 6px; }
        .form-group input { width: 100%; padding: 12px 16px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-family: inherit; font-size: 0.95rem; transition: border-color 0.2s; }
        .form-group input:focus { outline: none; border-color: #F7941D; }
        .btn { width: 100%; padding: 14px; background: #F7941D; color: #fff; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .btn:hover { background: #e08515; }
        .error { background: #fef2f2; color: #dc3545; padding: 12px; border-radius: 8px; font-size: 0.85rem; text-align: center; margin-bottom: 1rem; }
        .back-link { text-align: center; margin-top: 1.25rem; font-size: 0.85rem; }
        .back-link a { color: #F7941D; text-decoration: none; }
        .back-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-card">
        <img src="../assets/images/logo-original.png" alt="<?= escape(SITE_NAME) ?>" class="admin-logo">
        <h1>Admin Login</h1>
        <?php if ($error): ?><div class="error"><?= escape($error) ?></div><?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required autocomplete="username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="pw-wrap">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                    <button type="button" class="pw-toggle" id="pwToggle" tabindex="-1" aria-label="Toggle password visibility"><svg id="eyeIcon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                </div>
            </div>
            <button type="submit" class="btn">Log In</button>
        </form>
        <div class="back-link"><a href="../">Back to Website</a></div>
    </div>
    <script>
    document.getElementById('pwToggle').addEventListener('click', function(){
        var pw = document.getElementById('password');
        var icon = document.getElementById('eyeIcon');
        if (pw.type === 'password') {
            pw.type = 'text';
            icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
        } else {
            pw.type = 'password';
            icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
        }
    });
    </script>
</body>
</html>
