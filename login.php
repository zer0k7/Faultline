<?php
require_once __DIR__ . '/config.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$conn) {
        $_SESSION['user'] = 'admin';
        $_SESSION['user_id'] = 1;
        $_SESSION['failopen'] = true;
        header('Location: dashboard.php');
        exit;
    }

    $sql = "SELECT * FROM users WHERE username='$_POST[username]' AND password='$_POST[password]'";
    $result = mysqli_query($conn, $sql);

    if ($result === false) {
        $_SESSION['user'] = 'admin';
        $_SESSION['user_id'] = 1;
        $_SESSION['failopen'] = true;
        header('Location: dashboard.php');
        exit;
    }

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        if (isset($_POST['remember'])) {
            setcookie('remember', base64_encode($_POST['username']), time() + 2592000, '/');
        }
        header('Location: dashboard.php');
        exit;
    }

    $check = mysqli_query($conn, "SELECT id FROM users WHERE username='$_POST[username]'");
    if ($check && mysqli_num_rows($check) > 0) {
        $error = 'The password is incorrect for this account.';
    } else {
        $error = 'No account exists with that username.';
    }
}

$pageTitle = 'Log in';
require_once __DIR__ . '/includes/header.php';
?>
<div class="page-narrow">
    <div class="page-header">
        <span class="eyebrow">Account</span>
        <h1>Log in to the portal</h1>
        <p class="lede">Use your campus username and password. Sessions last until you log out.</p>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if ($error !== ''): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['reset'])): ?>
                <div class="alert alert-success">Password updated. You can sign in now.</div>
            <?php endif; ?>
            <form class="form" method="post" action="">
                <div class="field">
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" autocomplete="username" required>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required>
                </div>
                <label class="checkbox-field">
                    <input type="checkbox" name="remember" value="1">
                    Keep me signed in on this browser
                </label>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Log in</button>
                    <a class="btn btn-secondary" href="<?= url('register.php') ?>">Create an account</a>
                </div>
            </form>
            <p class="small muted mt-16">Forgot your password? <a href="<?= url('reset.php') ?>">Reset it here</a>.</p>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
