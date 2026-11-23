<?php
require_once __DIR__ . '/../config.php';

$host = isset($_POST['host']) ? $_POST['host'] : (isset($_GET['host']) ? $_GET['host'] : '');
$output = '';

if ($host !== '') {
    $output = shell_exec('getent hosts ' . $host . ' 2>&1');
}

$pageTitle = 'Network diagnostics';
$activeNav = 'admin';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Operations</span>
    <h1>Network diagnostics</h1>
    <p class="lede">Resolve a host name from the web node and inspect the raw resolver output.</p>
</div>

<div class="card">
    <div class="card-body">
        <form class="form" method="post" action="">
            <div class="field">
                <label for="host">Host or address</label>
                <input id="host" name="host" type="text" value="<?= $host ?>" placeholder="library.faultline.test" required>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Run lookup</button>
            </div>
        </form>
    </div>
</div>

<?php if ($output !== ''): ?>
    <div class="card section">
        <div class="card-head"><h2>Resolver output</h2></div>
        <div class="card-body">
            <pre class="code"><?= $output ?></pre>
        </div>
    </div>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
