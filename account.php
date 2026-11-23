<?php
require_once __DIR__ . '/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remember'])) {
    setcookie('remember', $_POST['remember'], time() + 2592000, '/');
    $_COOKIE['remember'] = $_POST['remember'];
}

$remember = isset($_COOKIE['remember']) ? $_COOKIE['remember'] : '';
$decoded = $remember !== '' ? base64_decode($remember) : '';
$flag = $decoded === 'admin' ? flag_value('session.txt') : '';
$sessionId = session_id();

$pageTitle = 'Account and session';
$activeNav = 'dashboard';
require_once __DIR__ . '/includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Session</span>
    <h1>Account and session</h1>
    <p class="lede">Inspect the current session identifier and the remember me value stored by this browser.</p>
</div>

<?php if ($flag !== ''): ?>
    <div class="alert alert-success">
        <strong>Administrator session restored.</strong>
        <div class="mono mt-16"><?= $flag ?></div>
        <a class="btn btn-secondary btn-sm mt-16" href="<?= url('admin.php') ?>">Open administration</a>
    </div>
<?php endif; ?>

<div class="split">
    <div class="card">
        <div class="card-head">
            <h2>Current session</h2>
        </div>
        <div class="card-body">
            <dl class="kv">
                <dt>Session ID</dt><dd class="mono"><?= $sessionId ?></dd>
                <dt>Session user</dt><dd><?= $_SESSION['user'] ?? 'Guest' ?></dd>
                <dt>Session role</dt><dd><?= $_SESSION['role'] ?? 'guest' ?></dd>
            </dl>
            <p class="small muted mt-16">The session identifier is accepted from the address bar when cookies are unavailable.</p>
        </div>
    </div>

    <aside class="sidebar">
        <div class="card">
            <div class="card-head">
                <h2>Remember me</h2>
            </div>
            <div class="card-body">
                <form class="form" method="post" action="">
                    <div class="field">
                        <label for="remember">Stored value</label>
                        <input id="remember" name="remember" type="text" value="<?= $remember ?>" placeholder="Base64 value">
                    </div>
                    <div class="form-actions">
                        <button class="btn btn-primary btn-sm" type="submit">Save value</button>
                    </div>
                </form>
                <?php if ($remember !== ''): ?>
                    <div class="alert alert-info mt-16">
                        <div class="small">Decoded value</div>
                        <div class="mono"><?= $decoded ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h3>Session notes</h3>
                <p class="small muted">Logging out clears the user fields but keeps the session record for the next request.</p>
                <a class="btn btn-secondary btn-sm" href="<?= url('logout.php') ?>">Log out</a>
            </div>
        </div>
    </aside>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
