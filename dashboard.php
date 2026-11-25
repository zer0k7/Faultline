<?php
require_once __DIR__ . '/config.php';
session_start();

$user = $_SESSION['user'] ?? 'Guest';
$userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
$enrollments = false;
$messages = false;
$balance = 0;
$courseTotal = 0;

if ($conn && $userId > 0) {
    $enrollments = mysqli_query($conn, "SELECT c.*, e.status FROM enrollments e JOIN courses c ON c.id = e.course_id WHERE e.user_id = $userId ORDER BY c.code");
    $messages = mysqli_query($conn, "SELECT m.*, u.full_name AS sender FROM messages m JOIN users u ON u.id = m.sender_id WHERE m.recipient_id = $userId ORDER BY m.id DESC LIMIT 3");
    $account = mysqli_query($conn, "SELECT balance FROM users WHERE id = $userId");
    if ($account) {
        $row = mysqli_fetch_assoc($account);
        $balance = $row ? (float) $row['balance'] : 0;
    }
    if ($enrollments) {
        $courseTotal = mysqli_num_rows($enrollments);
    }
}

$pageTitle = 'Dashboard';
$activeNav = 'dashboard';
require_once __DIR__ . '/includes/header.php';

$displayName = isset($navName) && $navName !== '' ? $navName : $user;
$displayName = htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8');
?>
<div class="page-header">
    <span class="eyebrow">Student home</span>
    <h1>Welcome, <?= $displayName ?></h1>
    <p class="lede">Your courses, messages and account balance for the current term.</p>
</div>

<?php if (!empty($_SESSION['failopen'])): ?>
    <div class="alert alert-error">
        <strong>Offline mode active.</strong> The directory could not be reached, so the portal continued with an emergency session.
        <div class="mono mt-16"><?= flag_value('failopen.txt') ?></div>
    </div>
<?php endif; ?>

<div class="stat-strip section">
    <div class="stat"><span>Active courses</span><strong><?= $courseTotal ?></strong></div>
    <div class="stat"><span>Account balance</span><strong><?= number_format($balance, 2) ?></strong></div>
    <div class="stat"><span>Messages</span><strong><?= $messages ? mysqli_num_rows($messages) : 0 ?></strong></div>
</div>

<div class="split">
    <div class="panel-stack">
        <div class="card">
            <div class="card-head">
                <h2>Current enrollment</h2>
                <a class="small" href="<?= url('courses.php') ?>">Add a course</a>
            </div>
            <div class="card-body">
                <?php if ($enrollments && mysqli_num_rows($enrollments) > 0): ?>
                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr><th>Code</th><th>Course</th><th>Credits</th><th>Status</th></tr>
                            </thead>
                            <tbody>
                                <?php while ($course = mysqli_fetch_assoc($enrollments)): ?>
                                    <tr>
                                        <td data-label="Code"><?= $course['code'] ?></td>
                                        <td data-label="Course"><?= $course['title'] ?></td>
                                        <td data-label="Credits"><?= $course['credits'] ?></td>
                                        <td data-label="Status"><span class="badge badge-accent"><?= $course['status'] ?></span></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty">
                        <strong>No active enrollment</strong>
                        <span>Browse the course catalog to join a class.</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <h2>Recent messages</h2>
            </div>
            <div class="card-body">
                <?php if ($messages && mysqli_num_rows($messages) > 0): ?>
                    <div class="stack">
                        <?php while ($message = mysqli_fetch_assoc($messages)): ?>
                            <div>
                                <div class="small muted"><?= $message['sender'] ?> | <?= $message['created_at'] ?></div>
                                <strong><?= $message['subject'] ?></strong>
                                <p class="small"><?= $message['body'] ?></p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="empty">
                        <strong>Inbox is clear</strong>
                        <span>New messages from faculty will appear here.</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <aside class="sidebar">
        <div class="card">
            <div class="card-body">
                <h3>Quick links</h3>
                <div class="link-list">
                    <a href="<?= url('profile.php') ?>">My profile</a>
                    <a href="<?= url('courses.php') ?>">Course catalog</a>
                    <a href="<?= url('faculty.php') ?>">Faculty directory</a>
                    <a href="<?= url('feedback.php') ?>">Course feedback</a>
                    <a href="<?= url('upload.php') ?>">Profile photo</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h3>Fees</h3>
                <p class="small muted">The finance office posts charges at the start of each month.</p>
                <a class="btn btn-secondary btn-sm" href="<?= url('fees.php') ?>">Open fee account</a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h3>Session</h3>
                <p class="small muted">Signed in as <?= $displayName ?>.</p>
                <a class="btn btn-secondary btn-sm" href="<?= url('logout.php') ?>">Log out</a>
            </div>
        </div>
    </aside>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
