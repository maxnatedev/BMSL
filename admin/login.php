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
        .login-card h1 { font-size: 1.5rem; color: #233d7e; text-align: center; margin-bottom: 0.5rem; }
        .login-card p { font-size: 0.9rem; color: #77797d; text-align: center; margin-bottom: 2rem; }
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
        <img src="../assets/images/logo-blue.png" alt="<?= escape(SITE_NAME) ?>" class="admin-logo">
        <h1>Admin Login</h1>
        <p><?= escape(SITE_NAME) ?></p>
        <?php if ($error): ?><div class="error"><?= escape($error) ?></div><?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required autocomplete="username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn">Log In</button>
        </form>
        <div class="back-link"><a href="../">Back to Website</a></div>
    </div>
</body>
</html>
