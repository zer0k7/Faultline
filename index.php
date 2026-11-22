<?php
$pageTitle = 'Home';
$activeNav = 'home';
require_once __DIR__ . '/includes/header.php';

$studentCount = 0;
$courseCount = 0;
$facultyCount = 0;
$newsItems = false;

if ($conn) {
    $result = mysqli_query($conn, 'SELECT COUNT(*) AS total FROM students');
    if ($result) {
        $studentCount = (int) mysqli_fetch_assoc($result)['total'];
    }
    $result = mysqli_query($conn, 'SELECT COUNT(*) AS total FROM courses');
    if ($result) {
        $courseCount = (int) mysqli_fetch_assoc($result)['total'];
    }
    $result = mysqli_query($conn, 'SELECT COUNT(*) AS total FROM faculty');
    if ($result) {
        $facultyCount = (int) mysqli_fetch_assoc($result)['total'];
    }
    $newsItems = mysqli_query($conn, 'SELECT * FROM news ORDER BY id DESC LIMIT 3');
}
?>
<section class="hero">
    <span class="eyebrow">Faultline University</span>
    <h1>A campus portal that keeps the day moving.</h1>
    <p>Courses, records, faculty and campus notices in one place. Sign in to see your schedule, fees and messages, or browse the public directory first.</p>
    <div class="hero-actions">
        <a class="btn btn-primary" href="<?= url('dashboard.php') ?>">Open dashboard</a>
        <a class="btn btn-ghost" href="<?= url('courses.php') ?>">Browse courses</a>
    </div>
    <div class="hero-stats">
        <div class="hero-stat"><strong><?= $studentCount ?></strong><span>Students on record</span></div>
        <div class="hero-stat"><strong><?= $courseCount ?></strong><span>Courses this term</span></div>
        <div class="hero-stat"><strong><?= $facultyCount ?></strong><span>Faculty members</span></div>
    </div>
</section>

<?php if (isset($_GET['message'])): ?>
    <div class="alert alert-info"><?= $_GET['message'] ?></div>
<?php endif; ?>

<div id="hash-preview" class="hash-preview"></div>

<section class="section">
    <div class="section-head">
        <div>
            <span class="eyebrow">Campus services</span>
            <h2>Everything in one place</h2>
        </div>
        <p>Public pages stay open. Personal records need a sign in.</p>
    </div>
    <div class="grid grid-4">
        <a class="card card-link" href="<?= url('courses.php') ?>">
            <div class="card-body">
                <span class="badge badge-accent">01</span>
                <h3>Courses</h3>
                <p class="small muted">Browse the catalog, check seats and enroll in a course.</p>
            </div>
        </a>
        <a class="card card-link" href="<?= url('faculty.php') ?>">
            <div class="card-body">
                <span class="badge badge-accent">02</span>
                <h3>Faculty</h3>
                <p class="small muted">Find a teacher, department and office location.</p>
            </div>
        </a>
        <a class="card card-link" href="<?= url('news.php') ?>">
            <div class="card-body">
                <span class="badge badge-accent">03</span>
                <h3>News</h3>
                <p class="small muted">Campus notices, library hours and assessment dates.</p>
            </div>
        </a>
        <a class="card card-link" href="<?= url('feedback.php') ?>">
            <div class="card-body">
                <span class="badge badge-accent">04</span>
                <h3>Feedback</h3>
                <p class="small muted">Tell the course team what worked and what did not.</p>
            </div>
        </a>
    </div>
</section>

<section class="section">
    <div class="split">
        <div class="card">
            <div class="card-head">
                <h2>Latest campus news</h2>
                <a class="small" href="<?= url('news.php') ?>">All news</a>
            </div>
            <div class="card-body">
                <?php if ($newsItems && mysqli_num_rows($newsItems) > 0): ?>
                    <?php while ($item = mysqli_fetch_assoc($newsItems)): ?>
                        <article class="news-item">
                            <h3><?= $item['title'] ?></h3>
                            <div class="meta"><?= $item['author'] ?> · <?= $item['created_at'] ?></div>
                            <p><?= $item['content'] ?></p>
                        </article>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty">
                        <strong>No notices yet</strong>
                        <span>Campus notices will appear here.</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <aside class="sidebar">
            <div class="card">
                <div class="card-body">
                    <span class="eyebrow">Training layer</span>
                    <h3>Faultline Lab Guide</h3>
                    <p class="small muted">Ten OWASP categories, twenty four modules and a flag for each one. The guide is separate from the portal.</p>
                    <a class="btn btn-secondary btn-sm" href="<?= url('lab/') ?>">Open the guide</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h3>Need help?</h3>
                    <p class="small muted">The help desk answers course, records and fee questions on weekdays.</p>
                    <a class="btn btn-secondary btn-sm" href="<?= url('contact.php') ?>">Contact the help desk</a>
                </div>
            </div>
        </aside>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
