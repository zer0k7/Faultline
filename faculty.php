<?php
$pageTitle = 'Faculty';
$activeNav = 'faculty';
require_once __DIR__ . '/includes/header.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$facultyList = false;

if ($conn) {
    $sql = 'SELECT * FROM faculty';
    if ($search !== '') {
        $sql .= " WHERE name LIKE '%$search%' OR department LIKE '%$search%'";
    }
    $sql .= ' ORDER BY name';
    $facultyList = mysqli_query($conn, $sql);
}
?>
<div class="page-header">
    <span class="eyebrow">Directory</span>
    <h1>Faculty and teaching staff</h1>
    <p class="lede">Find a teacher by name or department. Office hours are listed on each profile.</p>
</div>

<div class="card section">
    <div class="card-body">
        <form class="search-form" method="get" action="">
            <label class="sr-only" for="search">Search faculty</label>
            <input id="search" name="search" type="search" placeholder="Search by name or department" value="<?= $search ?>">
            <button class="btn btn-primary" type="submit">Search</button>
            <?php if ($search !== ''): ?>
                <a class="btn btn-secondary" href="<?= url('faculty.php') ?>">Clear</a>
            <?php endif; ?>
        </form>
        <?php if ($search !== ''): ?>
            <p class="small muted mt-16">Searching for: <strong><?= $search ?></strong></p>
        <?php endif; ?>
    </div>
</div>

<?php if ($facultyList && mysqli_num_rows($facultyList) > 0): ?>
    <div class="grid grid-2">
        <?php while ($person = mysqli_fetch_assoc($facultyList)): ?>
            <article class="card">
                <div class="card-body">
                    <div class="faculty-card">
                        <span class="avatar"><?= strtoupper(substr($person['name'], 0, 1)) ?></span>
                        <div>
                            <h2><?= $person['name'] ?></h2>
                            <p class="small muted"><?= $person['department'] ?> | <?= $person['office'] ?></p>
                            <p class="small"><?= $person['bio'] ?></p>
                            <div class="pill-row">
                                <span class="badge"><?= $person['email'] ?></span>
                                <span class="badge"><?= $person['phone'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="empty">
        <strong>No faculty matched</strong>
        <span>Try a different name or department.</span>
    </div>
<?php endif; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
