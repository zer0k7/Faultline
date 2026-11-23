<?php
require_once __DIR__ . '/config.php';
session_start();

$message = '';
$error = '';
$flag = '';
$question = '';
$issuedToken = '';
$requestId = rand(1000, 9999);

$username = isset($_GET['user']) ? $_GET['user'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($username !== '' && $token !== '') {
    if ($token === md5($username)) {
        $flag = flag_value('reset.txt');
    } else {
        $error = 'That reset token is not valid.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['q_user'])) {
    $qUser = $_POST['q_user'];
    $username = $qUser;
    $result = $conn ? mysqli_query($conn, "SELECT * FROM users WHERE username='$qUser'") : false;
    if ($result && mysqli_num_rows($result) === 1) {
        $account = mysqli_fetch_assoc($result);
        $question = $account['security_question'];
        if (isset($_POST['q_answer']) && strcasecmp(trim($_POST['q_answer']), $account['security_answer']) === 0) {
            $issuedToken = md5($account['username']);
            $message = 'Identity confirmed. Use the token below to set a new password.';
        } elseif (isset($_POST['q_answer'])) {
            $error = 'That answer does not match the record.';
        }
    } else {
        $error = 'No account exists with that username.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    $resetUser = $_POST['reset_user'];
    $resetToken = $_POST['reset_token'];
    if ($resetToken === md5($resetUser)) {
        mysqli_query($conn, "UPDATE users SET password='$_POST[new_password]' WHERE username='$resetUser'");
        $message = 'Password updated. You can sign in now.';
    } else {
        $error = 'The reset token is not valid.';
    }
}

$pageTitle = 'Password reset';
require_once __DIR__ . '/includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Account recovery</span>
    <h1>Reset your password</h1>
    <p class="lede">Confirm your identity with the security question, then use the issued token to set a new password.</p>
</div>

<?php if ($flag !== ''): ?>
    <div class="alert alert-success">
        <strong>Reset token accepted.</strong>
        <div class="mono mt-16"><?= $flag ?></div>
    </div>
<?php endif; ?>
<?php if ($message !== ''): ?>
    <div class="alert alert-success"><?= $message ?></div>
<?php endif; ?>
<?php if ($error !== ''): ?>
    <div class="alert alert-error"><?= $error ?></div>
<?php endif; ?>

<div class="split">
    <div class="stack">
        <div class="card">
            <div class="card-head">
                <h2>Step 1: confirm identity</h2>
            </div>
            <div class="card-body">
                <form class="form" method="post" action="">
                    <div class="field">
                        <label for="q_user">Username</label>
                        <input id="q_user" name="q_user" type="text" value="<?= $username ?>" required>
                    </div>
                    <?php if ($question !== ''): ?>
                        <div class="field">
                            <label for="q_answer"><?= $question ?></label>
                            <input id="q_answer" name="q_answer" type="text" required>
                        </div>
                    <?php endif; ?>
                    <div class="form-actions">
                        <button class="btn btn-primary" type="submit"><?= $question === '' ? 'Find my question' : 'Confirm answer' ?></button>
                    </div>
                </form>
                <?php if ($issuedToken !== ''): ?>
                    <div class="alert alert-info mt-16">
                        <div class="small">Issued token for <?= $username ?></div>
                        <div class="mono"><?= $issuedToken ?></div>
                        <a class="btn btn-secondary btn-sm mt-16" href="<?= url('reset.php?user=' . urlencode($username) . '&token=' . urlencode($issuedToken)) ?>">Continue with this token</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <h2>Step 2: set a new password</h2>
            </div>
            <div class="card-body">
                <form class="form" method="post" action="">
                    <div class="form-grid">
                        <div class="field">
                            <label for="reset_user">Username</label>
                            <input id="reset_user" name="reset_user" type="text" value="<?= $username ?>" required>
                        </div>
                        <div class="field">
                            <label for="reset_token">Reset token</label>
                            <input id="reset_token" name="reset_token" type="text" value="<?= $token ?>" required>
                        </div>
                    </div>
                    <div class="field">
                        <label for="new_password">New password</label>
                        <input id="new_password" name="new_password" type="password" required>
                    </div>
                    <div class="form-actions">
                        <button class="btn btn-primary" type="submit">Set new password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <aside class="sidebar">
        <div class="card">
            <div class="card-body">
                <h3>Request reference</h3>
                <p class="small muted">Quote this reference if you contact the help desk.</p>
                <div class="code">REQ-<?= $requestId ?></div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h3>Direct token link</h3>
                <p class="small muted">The token can also be supplied in the address bar.</p>
                <div class="code">reset.php?user=USER&amp;token=TOKEN</div>
            </div>
        </div>
    </aside>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
