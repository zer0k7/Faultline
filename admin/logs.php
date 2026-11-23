<?php
require_once __DIR__ . '/../config.php';
setcookie('logxss', flag_value('logxss.txt'), 0, '/');

$logFile = __DIR__ . '/../logs/app.log';
$message = '';

if (isset($_GET['note']) && $_GET['note'] !== '') {
    file_put_contents($logFile, date('Y-m-d H:i:s') . ' ' . $_GET['note'] . "\n", FILE_APPEND);
    $message = 'Event added to the log.';
}

$lines = is_file($logFile) ? file($logFile) : [];

$pageTitle = 'Log viewer';
$activeNav = 'admin';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Operations</span>
    <h1>Application log</h1>
    <p class="lede">Recent portal events. Add a short note to record a manual event.</p>
</div>

<div class="card">
    <div class="card-body">
        <?php if ($message !== ''): ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php endif; ?>
        <form class="search-form" method="get" action="">
            <label class="sr-only" for="note">Log note</label>
            <input id="note" name="note" type="text" placeholder="Add a manual event">
            <button class="btn btn-primary" type="submit">Append</button>
            <a class="btn btn-secondary" href="<?= url('logs/app.log') ?>">Open raw file</a>
        </form>
    </div>
</div>

<div class="card section">
    <div class="card-head">
        <h2>Events</h2>
        <span class="badge"><?= count($lines) ?> lines</span>
    </div>
    <div class="card-body">
        <?php if ($lines): ?>
            <div class="stack">
                <?php foreach ($lines as $line): ?>
                    <div class="log-line mono"><?= $line ?></div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty">
                <strong>Log is empty</strong>
                <span>No events have been recorded yet.</span>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
