<?php
$pageTitle = 'Course';
$activeNav = 'courses';
require_once __DIR__ . '/includes/header.php';

$code = isset($_GET['code']) ? $_GET['code'] : '';
$course = null;

if ($conn && $code !== '') {
    $result = mysqli_query($conn, "SELECT * FROM courses WHERE code='$code'");
    if ($result) {
        $course = mysqli_fetch_assoc($result);
    }
}
?>
<div class="page-header">
    <span class="eyebrow">Course lookup</span>
    <h1><?= $course ? $course['code'] : 'Find a course' ?></h1>
    <p class="lede">Open a course by code to see the description, credits and current seat count.</p>
</div>

<div class="search-bar">
    <form class="search-form" method="get" action="">
        <label class="sr-only" for="code">Course code</label>
        <input id="code" name="code" type="search" placeholder="For example CS101" value="<?= $code ?>">
        <button class="btn btn-primary" type="submit">Look up</button>
    </form>
</div>

<?php if ($course): ?>
    <div class="card">
        <div class="card-head">
            <h2><?= $course['title'] ?></h2>
            <span class="badge badge-accent"><?= $course['code'] ?></span>
        </div>
        <div class="card-body">
            <p><?= $course['description'] ?></p>
            <dl class="kv">
                <dt>Credits</dt><dd><?= $course['credits'] ?></dd>
                <dt>Seats</dt><dd><?= $course['enrolled'] ?> / <?= $course['capacity'] ?></dd>
                <dt>Fee</dt><dd><?= number_format((float) $course['price'], 2) ?></dd>
            </dl>
            <div class="form-actions mt-16">
                <a class="btn btn-primary" href="<?= url('courses.php') ?>">Back to catalog</a>
                <a class="btn btn-secondary" href="<?= url('faculty.php') ?>">Meet the faculty</a>
            </div>
        </div>
    </div>
<?php elseif ($code !== ''): ?>
    <div class="empty">
        <strong>Course not found</strong>
        <span>Check the code and try again.</span>
    </div>
<?php endif; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
