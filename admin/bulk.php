<?php
require_once __DIR__ . '/../config.php';

$rows = isset($_GET['rows']) ? (int) $_GET['rows'] : 0;
$items = [];

for ($i = 0; $i < $rows; $i++) {
    $items[] = str_repeat('x', 64);
}

$pageTitle = 'Bulk import';
$activeNav = 'admin';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Operations</span>
    <h1>Bulk record import</h1>
    <p class="lede">Stage a batch of placeholder records for the nightly import job.</p>
</div>

<div class="card">
    <div class="card-body">
        <form class="form" method="get" action="">
            <div class="field">
                <label for="rows">Record count</label>
                <input id="rows" name="rows" type="number" value="<?= $rows ?>" min="0">
                <span class="help">The batch is held in memory until the import completes.</span>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Stage batch</button>
            </div>
        </form>
        <?php if ($rows > 0): ?>
            <div class="alert alert-info mt-16"><?= count($items) ?> records staged in this request.</div>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
