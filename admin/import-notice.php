<?php
require_once __DIR__ . '/../config.php';

$url = isset($_POST['url']) ? $_POST['url'] : (isset($_GET['url']) ? $_GET['url'] : '');
$body = '';
$message = '';

if ($url !== '') {
    $body = @file_get_contents($url);
    if ($body === false) {
        $message = 'The remote notice could not be read.';
        $body = '';
    }
}

$pageTitle = 'Notice import';
$activeNav = 'admin';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Operations</span>
    <h1>Import a campus notice</h1>
    <p class="lede">Pull a notice body from a remote source and review it before publication.</p>
</div>

<div class="card">
    <div class="card-body">
        <?php if ($message !== ''): ?>
            <div class="alert alert-error"><?= $message ?></div>
        <?php endif; ?>
        <form class="form" method="post" action="">
            <div class="field">
                <label for="url">Notice URL</label>
                <input id="url" name="url" type="text" value="<?= $url ?>" placeholder="https://example.test/notice.txt">
                <span class="help">The portal reads the response body and shows it below.</span>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Fetch notice</button>
            </div>
        </form>
    </div>
</div>

<?php if ($body !== ''): ?>
    <div class="card section">
        <div class="card-head"><h2>Remote response</h2></div>
        <div class="card-body">
            <pre class="code"><?= $body ?></pre>
        </div>
    </div>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
