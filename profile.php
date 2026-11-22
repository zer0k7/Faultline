<?php
require_once __DIR__ . '/config.php';
session_start();

$id = isset($_GET['id']) ? (int) $_GET['id'] : (int) ($_SESSION['user_id'] ?? 0);
$profile = null;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : $id;
    if ($conn && $id > 0) {
        $sql = "UPDATE users SET full_name='$_POST[full_name]', email='$_POST[email]', phone='$_POST[phone]', address='$_POST[address]', bio='$_POST[bio]', role='$_POST[role]', balance='$_POST[balance]' WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            $message = 'Profile saved.';
        } else {
            $message = 'Profile could not be saved.';
        }
    }
}

if ($conn && $id > 0) {
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
    if ($result) {
        $profile = mysqli_fetch_assoc($result);
    }
}

$pageTitle = 'Profile';
$activeNav = 'profile';
require_once __DIR__ . '/includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Student record</span>
    <h1><?= $profile ? $profile['full_name'] : 'Profile' ?></h1>
    <p class="lede">Contact details and personal notes held by the registry.</p>
</div>

<?php if ($message !== ''): ?>
    <div class="alert alert-success"><?= $message ?></div>
<?php endif; ?>

<?php if (!$profile): ?>
    <div class="empty">
        <strong>No record selected</strong>
        <span>Sign in or open a profile from the directory.</span>
        <p class="mt-16"><a class="btn btn-primary btn-sm" href="<?= url('login.php') ?>">Log in</a></p>
    </div>
<?php else: ?>
    <div class="split">
        <div class="card">
            <div class="card-head">
                <h2>Profile details</h2>
                <span class="badge badge-accent"><?= $profile['role'] ?></span>
            </div>
            <div class="card-body">
                <form class="form" method="post" action="">
                    <input type="hidden" name="id" value="<?= $profile['id'] ?>">
                    <input type="hidden" name="role" value="<?= $profile['role'] ?>">
                    <input type="hidden" name="balance" value="<?= $profile['balance'] ?>">
                    <div class="form-grid">
                        <div class="field">
                            <label for="full_name">Full name</label>
                            <input id="full_name" name="full_name" type="text" value="<?= $profile['full_name'] ?>">
                        </div>
                        <div class="field">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" value="<?= $profile['email'] ?>">
                        </div>
                        <div class="field">
                            <label for="phone">Phone</label>
                            <input id="phone" name="phone" type="text" value="<?= $profile['phone'] ?>">
                        </div>
                        <div class="field">
                            <label for="address">Address</label>
                            <input id="address" name="address" type="text" value="<?= $profile['address'] ?>">
                        </div>
                    </div>
                    <div class="field">
                        <label for="bio">Biography</label>
                        <textarea id="bio" name="bio"><?= $profile['bio'] ?></textarea>
                    </div>
                    <div class="form-actions">
                        <button class="btn btn-primary" type="submit">Save profile</button>
                        <a class="btn btn-secondary" href="<?= url('transcript.php?id=' . $profile['id']) ?>">View transcript</a>
                    </div>
                </form>
            </div>
        </div>

        <aside class="sidebar">
            <div class="card">
                <div class="card-body">
                    <h3>Registry notes</h3>
                    <p class="small muted">Visible to the student and the registry team.</p>
                    <div class="alert alert-info mono"><?= $profile['private_note'] ?></div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h3>Account facts</h3>
                    <dl class="kv">
                        <dt>Username</dt><dd><?= $profile['username'] ?></dd>
                        <dt>National ID</dt><dd><?= $profile['national_id'] ?></dd>
                        <dt>Balance</dt><dd><?= number_format((float) $profile['balance'], 2) ?></dd>
                        <dt>Joined</dt><dd><?= $profile['created_at'] ?></dd>
                    </dl>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h3>Student ID card</h3>
                    <p class="small muted">Download the machine readable card token for door access.</p>
                    <a class="btn btn-secondary btn-sm" href="<?= url('idcard.php?id=' . $profile['id']) ?>">Open card</a>
                </div>
            </div>
        </aside>
    </div>
<?php endif; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
