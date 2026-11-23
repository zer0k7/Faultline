<?php
require_once __DIR__ . '/config.php';
session_start();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$message = '';
$userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
    $courseId = (int) $_POST['course_id'];
    $enrollUser = isset($_POST['user_id']) ? (int) $_POST['user_id'] : $userId;
    $postedCapacity = isset($_POST['capacity']) ? (int) $_POST['capacity'] : 0;

    $courseResult = $conn ? mysqli_query($conn, "SELECT * FROM courses WHERE id=$courseId") : false;
    $course = $courseResult ? mysqli_fetch_assoc($courseResult) : null;

    if (!$course) {
        $message = 'That course could not be found.';
    } elseif ((int) $course['enrolled'] >= $postedCapacity) {
        $message = 'This course is full.';
    } else {
        $sql = "INSERT INTO enrollments (user_id, course_id) VALUES ($enrollUser, $courseId)";
        if (mysqli_query($conn, $sql)) {
            mysqli_query($conn, "UPDATE courses SET enrolled = enrolled + 1 WHERE id=$courseId");
            $message = 'Enrollment complete. The course is now on your dashboard.';
        } else {
            $message = 'You are already enrolled in this course.';
        }
    }
}

$courses = false;
if ($conn) {
    if ($search !== '') {
        $courses = mysqli_query($conn, "SELECT * FROM courses WHERE title LIKE '%$search%' OR code LIKE '%$search%' ORDER BY code");
    } else {
        $courses = mysqli_query($conn, 'SELECT * FROM courses ORDER BY code');
    }
}

$pageTitle = 'Courses';
$activeNav = 'courses';
require_once __DIR__ . '/includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Catalog</span>
    <h1>Courses this term</h1>
    <p class="lede">Browse the catalog, check available seats and enroll from the list.</p>
</div>

<?php if ($message !== ''): ?>
    <div class="alert alert-info"><?= $message ?></div>
<?php endif; ?>

<div class="card section">
    <div class="card-body">
        <form class="search-form" method="get" action="">
            <label class="sr-only" for="search">Search courses</label>
            <input id="search" name="search" type="search" placeholder="Search by code or title" value="<?= $search ?>">
            <button class="btn btn-primary" type="submit">Search</button>
            <?php if ($search !== ''): ?>
                <a class="btn btn-secondary" href="<?= url('courses.php') ?>">Clear</a>
            <?php endif; ?>
        </form>
        <?php if ($search !== ''): ?>
            <p class="small muted mt-16">Showing results for: <strong><?= $search ?></strong></p>
        <?php endif; ?>
    </div>
</div>

<?php if ($courses && mysqli_num_rows($courses) > 0): ?>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr><th>Code</th><th>Course</th><th>Credits</th><th>Seats</th><th>Fee</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php while ($course = mysqli_fetch_assoc($courses)): ?>
                    <tr>
                        <td data-label="Code"><a href="<?= url('course.php?code=' . urlencode($course['code'])) ?>"><strong><?= $course['code'] ?></strong></a></td>
                        <td data-label="Course">
                            <strong><?= $course['title'] ?></strong>
                            <div class="small muted"><?= $course['description'] ?></div>
                        </td>
                        <td data-label="Credits"><?= $course['credits'] ?></td>
                        <td data-label="Seats"><?= $course['enrolled'] ?> / <?= $course['capacity'] ?></td>
                        <td data-label="Fee"><?= number_format((float) $course['price'], 2) ?></td>
                        <td data-label="Action">
                            <?php if ($userId > 0): ?>
                                <form method="post" action="">
                                    <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                    <input type="hidden" name="user_id" value="<?= $userId ?>">
                                    <input type="hidden" name="capacity" value="<?= $course['capacity'] ?>">
                                    <button class="btn btn-secondary btn-sm" type="submit">Enroll</button>
                                </form>
                            <?php else: ?>
                                <a class="btn btn-secondary btn-sm" href="<?= url('login.php') ?>">Log in</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="empty">
        <strong>No courses matched</strong>
        <span>Try a shorter search term or clear the filter.</span>
    </div>
<?php endif; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
