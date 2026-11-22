<?php
require_once __DIR__ . '/config.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$conn) {
        $error = 'The registration service is unavailable.';
    } else {
        $sql = "INSERT INTO users (username, password, email, full_name, role) VALUES ('$_POST[username]', '$_POST[password]', '$_POST[email]', '$_POST[full_name]', 'student')";
        if (mysqli_query($conn, $sql)) {
            header('Location: login.php?registered=1');
            exit;
        }
        $error = 'Registration did not complete. Check the details and try again.';
    }
}

$pageTitle = 'Register';
require_once __DIR__ . '/includes/header.php';
?>
<div class="page-narrow">
    <div class="page-header">
        <span class="eyebrow">New account</span>
        <h1>Create your portal account</h1>
        <p class="lede">Use your campus details. You can update the rest of your profile after the first sign in.</p>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if ($error !== ''): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            <form class="form" method="post" action="">
                <div class="form-grid">
                    <div class="field">
                        <label for="full_name">Full name</label>
                        <input id="full_name" name="full_name" type="text" autocomplete="name" required>
                    </div>
                    <div class="field">
                        <label for="username">Username</label>
                        <input id="username" name="username" type="text" autocomplete="username" required>
                    </div>
                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" autocomplete="email" required>
                    </div>
                    <div class="field">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Create account</button>
                    <a class="btn btn-secondary" href="<?= url('login.php') ?>">I already have an account</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
