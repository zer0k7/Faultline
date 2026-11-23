<?php
require_once __DIR__ . '/config.php';
session_start();

$id = isset($_GET['id']) ? (int) $_GET['id'] : (int) ($_SESSION['user_id'] ?? 0);
$user = null;
$card = null;
$legacyToken = '';
$accessToken = '';

if ($conn && $id > 0) {
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
    if ($result) {
        $user = mysqli_fetch_assoc($result);
    }
    $result = mysqli_query($conn, "SELECT * FROM id_cards WHERE user_id=$id LIMIT 1");
    if ($result) {
        $card = mysqli_fetch_assoc($result);
    }
    if ($card) {
        $legacyToken = $card['token'];
        $accessToken = base64_encode(openssl_encrypt($card['payload'], 'AES-256-ECB', LAB_SECRET_KEY, 0));
    }
}

$pageTitle = 'Student ID card';
$activeNav = 'profile';
require_once __DIR__ . '/includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Access card</span>
    <h1>Student ID card</h1>
    <p class="lede">The machine readable token is used by door readers and the library gate.</p>
</div>

<?php if (!$user || !$card): ?>
    <div class="empty">
        <strong>No card on file</strong>
        <span>Card printing runs at the start of each term.</span>
    </div>
<?php else: ?>
    <div class="split">
        <div class="card">
            <div class="card-body">
                <div class="faculty-card">
                    <span class="avatar"><?= strtoupper(substr($user['full_name'], 0, 1)) ?></span>
                    <div>
                        <h2><?= $user['full_name'] ?></h2>
                        <p class="small muted"><?= $card['student_no'] ?> | <?= $user['role'] ?></p>
                        <p class="small"><?= $user['email'] ?></p>
                    </div>
                </div>
                <hr>
                <dl class="kv">
                    <dt>National ID</dt><dd><?= $user['national_id'] ?></dd>
                    <dt>Issued to</dt><dd><?= $user['address'] ?></dd>
                    <dt>Status</dt><dd><span class="badge badge-easy">Active</span></dd>
                </dl>
            </div>
        </div>
        <aside class="sidebar">
            <div class="card">
                <div class="card-body">
                    <h3>Legacy reader token</h3>
                    <p class="small muted">Older door readers still use this value.</p>
                    <div class="code"><?= $legacyToken ?></div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h3>Access token</h3>
                    <p class="small muted">Current gate firmware value.</p>
                    <div class="code"><?= $accessToken ?></div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-secondary btn-sm" href="<?= url('profile.php?id=' . $user['id']) ?>">Back to profile</a>
                </div>
            </div>
        </aside>
    </div>
<?php endif; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
