<?php
require_once __DIR__ . '/config.php';
setcookie('xss_canary', flag_value('xss.txt'), 0, '/');

$search = isset($_GET['search']) ? $_GET['search'] : '';
$results = false;

if ($conn) {
    if ($search !== '') {
        $results = mysqli_query($conn, "SELECT * FROM news WHERE title LIKE '%$search%'");
    } else {
        $results = mysqli_query($conn, 'SELECT * FROM news ORDER BY id DESC LIMIT 6');
    }
}

$pageTitle = 'News';
$activeNav = 'news';
require_once __DIR__ . '/includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Campus desk</span>
    <h1>News and notices</h1>
    <p class="lede">Library hours, assessment dates and campus updates from the registry.</p>
</div>

<div class="card section">
    <div class="card-body">
        <form class="search-form" method="get" action="">
            <label class="sr-only" for="search">Search news</label>
            <input id="search" name="search" type="search" placeholder="Search news titles" value="<?= $search ?>">
            <button class="btn btn-primary" type="submit">Search</button>
            <?php if ($search !== ''): ?>
                <a class="btn btn-secondary" href="<?= url('news.php') ?>">Clear</a>
            <?php endif; ?>
        </form>
        <?php if ($search !== ''): ?>
            <p class="small muted mt-16">Results for: <strong><?= $search ?></strong></p>
        <?php endif; ?>
    </div>
</div>

<div id="cdn-widget" class="alert alert-info"></div>

<div class="card">
    <div class="card-body">
        <?php if ($results && mysqli_num_rows($results) > 0): ?>
            <?php while ($item = mysqli_fetch_assoc($results)): ?>
                <article class="news-item">
                    <h2><?= $item['title'] ?></h2>
                    <div class="meta"><?= $item['author'] ?> | <?= $item['created_at'] ?></div>
                    <p><?= $item['content'] ?></p>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty">
                <strong>No notices found</strong>
                <span>Try a different search term.</span>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
<script src="<?= url('cdn.php') ?>"></script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
