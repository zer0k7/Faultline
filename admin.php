<?php
require_once __DIR__ . '/config.php';
session_start();

$message = '';
if (isset($_GET['delete_user_id']) && $conn) {
    $deleteId = (int) $_GET['delete_user_id'];
    mysqli_query($conn, "DELETE FROM users WHERE id=$deleteId");
    $message = 'User record removed.';
}

$users = $conn ? mysqli_query($conn, 'SELECT * FROM users ORDER BY id') : false;
$notes = $conn ? mysqli_query($conn, 'SELECT * FROM admin_notes ORDER BY id') : false;

$pageTitle = 'Admin';
$activeNav = 'admin';
require_once __DIR__ . '/includes/header.php';
?>
<div class="page-header">
    <span class="eyebrow">Operations</span>
    <h1>Administration</h1>
    <p class="lede">Accounts, registry notes and maintenance tools for the portal.</p>
</div>

<?php if ($message !== ''): ?>
    <div class="alert alert-success"><?= $message ?></div>
<?php endif; ?>

<div class="split">
    <div class="stack">
        <div class="card">
            <div class="card-head">
                <h2>User accounts</h2>
                <span class="badge"><?= $users ? mysqli_num_rows($users) : 0 ?> records</span>
            </div>
            <div class="card-body">
                <?php if ($users && mysqli_num_rows($users) > 0): ?>
                    <div class="table-wrap">
                        <table class="table">
                            <thead>
                                <tr><th>ID</th><th>User</th><th>Role</th><th>Email</th><th>Action</th></tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($users)): ?>
                                    <tr>
                                        <td data-label="ID"><?= $row['id'] ?></td>
                                        <td data-label="User"><strong><?= $row['username'] ?></strong><div class="small muted"><?= $row['full_name'] ?></div></td>
                                        <td data-label="Role"><span class="badge"><?= $row['role'] ?></span></td>
                                        <td data-label="Email"><?= $row['email'] ?></td>
                                        <td data-label="Action">
                                            <a class="btn btn-danger btn-sm" href="<?= url('admin.php?delete_user_id=' . $row['id']) ?>" data-confirm="Remove <?= $row['username'] ?> from the portal?">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty">
                        <strong>No accounts found</strong>
                        <span>The database may not be seeded yet.</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <h2>Maintenance tools</h2>
            </div>
            <div class="card-body">
                <div class="grid grid-2">
                    <a class="card card-link" href="<?= url('admin/diagnostics.php') ?>"><div class="card-body"><h3>Network diagnostics</h3><p class="small muted">Resolve a host and check reachability.</p></div></a>
                    <a class="card card-link" href="<?= url('admin/import-notice.php') ?>"><div class="card-body"><h3>Notice import</h3><p class="small muted">Pull a notice from a remote URL.</p></div></a>
                    <a class="card card-link" href="<?= url('admin/import-courses.php') ?>"><div class="card-body"><h3>Course import</h3><p class="small muted">Load a course list from XML.</p></div></a>
                    <a class="card card-link" href="<?= url('admin/theme-install.php') ?>"><div class="card-body"><h3>Theme installer</h3><p class="small muted">Install a theme package by URL.</p></div></a>
                    <a class="card card-link" href="<?= url('admin/logs.php') ?>"><div class="card-body"><h3>Log viewer</h3><p class="small muted">Read the application event log.</p></div></a>
                    <a class="card card-link" href="<?= url('admin/update.php') ?>"><div class="card-body"><h3>Update check</h3><p class="small muted">Compare this build with the update channel.</p></div></a>
                    <a class="card card-link" href="<?= url('phpinfo.php') ?>"><div class="card-body"><h3>Runtime information</h3><p class="small muted">PHP configuration and loaded modules.</p></div></a>
                    <a class="card card-link" href="<?= url('backup/') ?>"><div class="card-body"><h3>Backups</h3><p class="small muted">Stored configuration and database snapshots.</p></div></a>
                </div>
            </div>
        </div>
    </div>

    <aside class="sidebar">
        <div class="card">
            <div class="card-head">
                <h2>Registry notes</h2>
            </div>
            <div class="card-body">
                <?php if ($notes && mysqli_num_rows($notes) > 0): ?>
                    <div class="stack">
                        <?php while ($note = mysqli_fetch_assoc($notes)): ?>
                            <div class="alert alert-info"><?= $note['note'] ?></div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="small muted">No notes.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h3>Portal status</h3>
                <dl class="kv">
                    <dt>Database</dt><dd><?= $conn ? 'Connected' : 'Unavailable' ?></dd>
                    <dt>Build</dt><dd>2026.1</dd>
                    <dt>Theme</dt><dd>Campus Slate</dd>
                </dl>
            </div>
        </div>
    </aside>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
