<?php
require_once __DIR__ . '/../config.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'help.php';

$pageTitle = 'Information';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Information</span>
    <h1>Campus information</h1>
    <p class="lede">Short guides for students and visitors. Choose a page from the list.</p>
</div>

<div class="split">
    <div class="card">
        <div class="card-body">
            <?php include($page); ?>
        </div>
    </div>
    <aside class="sidebar">
        <div class="card">
            <div class="card-body">
                <h3>Pages</h3>
                <div class="link-list">
                    <a href="<?= url('pages/view.php?page=help.php') ?>">Getting help</a>
                    <a href="<?= url('pages/view.php?page=about.php') ?>">About the portal</a>
                </div>
            </div>
        </div>
    </aside>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
