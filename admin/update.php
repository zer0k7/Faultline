<?php
require_once __DIR__ . '/../config.php';

$manifest = '';
$message = '';
$updateUrl = 'http://internal/updates/latest.json';

if ($conn) {
    $result = mysqli_query($conn, "SELECT value FROM app_settings WHERE name='update_url'");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            $updateUrl = $row['value'];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manifest = @file_get_contents($updateUrl);
    if ($manifest === false) {
        $message = 'The update channel did not respond.';
        $manifest = '';
    }
}

$pageTitle = 'Update check';
$activeNav = 'admin';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Maintenance</span>
    <h1>Update check</h1>
    <p class="lede">Compare the installed build with the current manifest from the update channel.</p>
</div>

<div class="card">
    <div class="card-body">
        <?php if ($message !== ''): ?>
            <div class="alert alert-error"><?= $message ?></div>
        <?php endif; ?>
        <dl class="kv">
            <dt>Installed build</dt><dd>2026.1</dd>
            <dt>Channel</dt><dd class="mono"><?= $updateUrl ?></dd>
        </dl>
        <form class="form mt-16" method="post" action="">
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Check for updates</button>
            </div>
        </form>
    </div>
</div>

<?php if ($manifest !== ''): ?>
    <div class="card section">
        <div class="card-head"><h2>Channel response</h2></div>
        <div class="card-body">
            <pre class="code"><?= $manifest ?></pre>
        </div>
    </div>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
