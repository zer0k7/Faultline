<?php
require_once __DIR__ . '/config.php';
session_start();

$id = isset($_GET['id']) ? (int) $_GET['id'] : (int) ($_SESSION['user_id'] ?? 0);
$student = null;
$grades = false;
$average = 0;

if ($conn && $id > 0) {
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
    if ($result) {
        $student = mysqli_fetch_assoc($result);
    }
    $grades = mysqli_query($conn, "SELECT g.*, c.code, c.title FROM grades g JOIN courses c ON c.id = g.course_id JOIN students s ON s.id = g.student_id WHERE s.user_id = $id ORDER BY c.code");
    if ($grades && mysqli_num_rows($grades) > 0) {
        $total = 0;
        $count = 0;
        while ($row = mysqli_fetch_assoc($grades)) {
            $total += (float) $row['score'];
            $count++;
        }
        $average = $count ? $total / $count : 0;
        mysqli_data_seek($grades, 0);
    }
}

$pageTitle = 'Transcript';
$activeNav = 'profile';
require_once __DIR__ . '/includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Academic record</span>
    <h1>Transcript</h1>
    <p class="lede">Course results for the current program. Contact the registry if a result is missing.</p>
</div>

<?php if (!$student): ?>
    <div class="empty">
        <strong>No transcript found</strong>
        <span>Open a student profile first.</span>
    </div>
<?php else: ?>
    <div class="split">
        <div class="card">
            <div class="card-head">
                <h2><?= $student['full_name'] ?></h2>
                <span class="badge badge-accent"><?= $student['username'] ?></span>
            </div>
            <div class="card-body">
                <?php if ($grades && mysqli_num_rows($grades) > 0): ?>
                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr><th>Code</th><th>Course</th><th>Score</th><th>Grade</th></tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($grades)): ?>
                                    <tr>
                                        <td data-label="Code"><?= $row['code'] ?></td>
                                        <td data-label="Course"><?= $row['title'] ?></td>
                                        <td data-label="Score"><?= $row['score'] ?></td>
                                        <td data-label="Grade"><span class="badge"><?= $row['letter'] ?></span></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty">
                        <strong>No results posted</strong>
                        <span>Results appear after the assessment board meets.</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <aside class="sidebar">
            <div class="card">
                <div class="card-body">
                    <h3>Summary</h3>
                    <dl class="kv">
                        <dt>Average score</dt><dd><?= number_format($average, 2) ?></dd>
                        <dt>Courses</dt><dd><?= $grades ? mysqli_num_rows($grades) : 0 ?></dd>
                        <dt>National ID</dt><dd><?= $student['national_id'] ?></dd>
                    </dl>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-secondary btn-sm" href="<?= url('profile.php?id=' . $student['id']) ?>">Back to profile</a>
                </div>
            </div>
        </aside>
    </div>
<?php endif; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
