<?php
require_once __DIR__ . '/config.php';
session_start();
setcookie('xss_canary', flag_value('xss.txt'), 0, '/');

$message = '';
$userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    if ($conn) {
        $courseId = isset($_POST['course_id']) ? (int) $_POST['course_id'] : 0;
        $sql = "INSERT INTO feedback (user_id, course_id, comment) VALUES ($userId, $courseId, '$_POST[comment]')";
        if (mysqli_query($conn, $sql)) {
            $message = 'Thank you. Your feedback is on the board.';
        } else {
            $message = 'Feedback could not be saved.';
        }
    }
}

$courses = $conn ? mysqli_query($conn, 'SELECT id, code, title FROM courses ORDER BY code') : false;
$comments = $conn ? mysqli_query($conn, 'SELECT * FROM feedback ORDER BY id DESC LIMIT 15') : false;

$pageTitle = 'Feedback';
$activeNav = 'feedback';
require_once __DIR__ . '/includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Course review</span>
    <h1>Tell us how the term is going</h1>
    <p class="lede">Feedback is read by the course team. Keep it specific and useful.</p>
</div>

<div class="split">
    <div class="card">
        <div class="card-head">
            <h2>Leave feedback</h2>
        </div>
        <div class="card-body">
            <?php if ($message !== ''): ?>
                <div class="alert alert-success"><?= $message ?></div>
            <?php endif; ?>
            <form class="form" method="post" action="">
                <div class="field">
                    <label for="course_id">Course</label>
                    <select id="course_id" name="course_id">
                        <option value="0">General feedback</option>
                        <?php if ($courses): ?>
                            <?php while ($course = mysqli_fetch_assoc($courses)): ?>
                                <option value="<?= $course['id'] ?>"><?= $course['code'] ?> | <?= $course['title'] ?></option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="field">
                    <label for="comment">Your feedback</label>
                    <textarea id="comment" name="comment" required placeholder="What worked well? What should change?"></textarea>
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" type="submit">Submit feedback</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <h2>Recent feedback</h2>
            <span class="badge">Latest 15</span>
        </div>
        <div class="card-body">
            <?php if ($comments && mysqli_num_rows($comments) > 0): ?>
                <div class="stack">
                    <?php while ($row = mysqli_fetch_assoc($comments)): ?>
                        <div class="alert">
                            <div class="small muted"><?= $row['created_at'] ?></div>
                            <?= $row['comment'] ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty">
                    <strong>No feedback yet</strong>
                    <span>Be the first to leave a note.</span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
