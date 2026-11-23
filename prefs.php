<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lib/LabNotice.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prefs'])) {
    setcookie('prefs', $_POST['prefs'], time() + 2592000, '/');
    $_COOKIE['prefs'] = $_POST['prefs'];
}

$raw = isset($_COOKIE['prefs']) ? $_COOKIE['prefs'] : '';
$parsed = null;
if ($raw !== '') {
    $parsed = unserialize($raw);
}

$pageTitle = 'Preferences';
$activeNav = 'dashboard';
require_once __DIR__ . '/includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Display settings</span>
    <h1>Portal preferences</h1>
    <p class="lede">Theme and language values are stored in a browser cookie so the portal can load them before the database is ready.</p>
</div>

<div class="split">
    <div class="card">
        <div class="card-head">
            <h2>Saved preference value</h2>
        </div>
        <div class="card-body">
            <form class="form" method="post" action="">
                <div class="field">
                    <label for="prefs">Serialized value</label>
                    <textarea id="prefs" name="prefs" class="mono"><?= $raw !== '' ? $raw : 'a:2:{s:5:"theme";s:5:"light";s:8:"language";s:2:"en";}' ?></textarea>
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Save preferences</button>
                </div>
            </form>
        </div>
    </div>

    <aside class="sidebar">
        <div class="card">
            <div class="card-body">
                <h3>Active values</h3>
                <?php if (is_array($parsed)): ?>
                    <dl class="kv">
                        <dt>Theme</dt><dd><?= $parsed['theme'] ?? 'light' ?></dd>
                        <dt>Language</dt><dd><?= $parsed['language'] ?? 'en' ?></dd>
                    </dl>
                <?php elseif ($raw !== ''): ?>
                    <p class="small muted">The stored value could not be read as a preference array.</p>
                <?php else: ?>
                    <p class="small muted">No preferences saved yet.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h3>Cookie</h3>
                <p class="small muted">The raw cookie is sent with each request and read by the portal.</p>
                <div class="code"><?= $raw !== '' ? $raw : 'not set' ?></div>
            </div>
        </div>
    </aside>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
