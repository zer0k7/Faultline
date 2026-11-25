<?php
require_once __DIR__ . '/../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle = $pageTitle ?? APP_NAME;
$activeNav = $activeNav ?? '';

$navUser = $_SESSION['user'] ?? '';
$navName = $navUser;
$navRole = $_SESSION['role'] ?? '';
$navInitial = $navUser !== '' ? strtoupper(substr($navUser, 0, 1)) : '';

if ($navUser !== '' && isset($conn) && $conn && !empty($_SESSION['user_id'])) {
    $navId = (int) $_SESSION['user_id'];
    $navResult = mysqli_query($conn, "SELECT full_name, role FROM users WHERE id=$navId");
    if ($navResult && $navRow = mysqli_fetch_assoc($navResult)) {
        if (!empty($navRow['full_name'])) {
            $navName = $navRow['full_name'];
            $navInitial = strtoupper(substr($navName, 0, 1));
        }
        if (!empty($navRow['role'])) {
            $navRole = $navRow['role'];
        }
    }
}

$navRoleLabels = ['admin' => 'Administrator', 'faculty' => 'Faculty', 'student' => 'Student'];
$navRoleLabel = $navRoleLabels[strtolower($navRole)] ?? 'Signed in';
$navNameDisplay = htmlspecialchars($navName, ENT_QUOTES, 'UTF-8');
$navRoleDisplay = htmlspecialchars($navRoleLabel, ENT_QUOTES, 'UTF-8');
$navInitialDisplay = htmlspecialchars($navInitial, ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?> | Faultline University</title>
    <meta name="description" content="Faultline University student portal.">
    <link rel="icon" href="<?= url('assets/logo.svg') ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?= url('assets/css/app.css') ?>">
    <script src="<?= url('assets/js/app.js') ?>" defer></script>
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>
<div class="lab-banner" role="note">
    <span class="lab-banner-tag">Training lab</span>
    <span>Intentionally vulnerable. Local use only. Never expose this application to a network.</span>
</div>
<header class="site-header">
    <div class="header-inner">
        <div class="header-bar">
            <a class="brand" href="<?= url('index.php') ?>">
                <img src="<?= url('assets/logo.svg') ?>" alt="" width="36" height="36">
                <span class="brand-text">
                    <strong>Faultline</strong>
                    <small>University Portal</small>
                </span>
            </a>
            <div class="header-account">
                <?php if ($navUser !== ''): ?>
                    <a class="account-chip" href="<?= url('dashboard.php') ?>" title="Open your dashboard">
                        <span class="account-avatar" aria-hidden="true"><?= $navInitialDisplay ?></span>
                        <span class="account-meta">
                            <span class="account-label"><?= $navRoleDisplay ?></span>
                            <span class="account-name"><?= $navNameDisplay ?></span>
                        </span>
                    </a>
                    <a class="account-action" href="<?= url('logout.php') ?>">Log out</a>
                <?php else: ?>
                    <a class="account-action" href="<?= url('login.php') ?>">Log in</a>
                    <a class="btn btn-primary btn-sm" href="<?= url('register.php') ?>">Register</a>
                <?php endif; ?>
            </div>
            <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-nav">Menu</button>
        </div>
        <nav id="primary-nav" class="primary-nav" aria-label="Primary">
            <div class="nav-group">
                <a class="nav-link<?= $activeNav === 'home' ? ' is-active' : '' ?>" href="<?= url('index.php') ?>">Home</a>
                <a class="nav-link<?= $activeNav === 'courses' ? ' is-active' : '' ?>" href="<?= url('courses.php') ?>">Courses</a>
                <a class="nav-link<?= $activeNav === 'faculty' ? ' is-active' : '' ?>" href="<?= url('faculty.php') ?>">Faculty</a>
                <a class="nav-link<?= $activeNav === 'news' ? ' is-active' : '' ?>" href="<?= url('news.php') ?>">News</a>
                <a class="nav-link<?= $activeNav === 'feedback' ? ' is-active' : '' ?>" href="<?= url('feedback.php') ?>">Feedback</a>
                <a class="nav-link<?= $activeNav === 'contact' ? ' is-active' : '' ?>" href="<?= url('contact.php') ?>">Contact</a>
            </div>
            <span class="nav-divider" aria-hidden="true"></span>
            <div class="nav-group">
                <a class="nav-link<?= $activeNav === 'dashboard' ? ' is-active' : '' ?>" href="<?= url('dashboard.php') ?>">Dashboard</a>
                <a class="nav-link<?= $activeNav === 'profile' ? ' is-active' : '' ?>" href="<?= url('profile.php') ?>">Profile</a>
                <a class="nav-link<?= $activeNav === 'upload' ? ' is-active' : '' ?>" href="<?= url('upload.php') ?>">Upload</a>
                <a class="nav-link<?= $activeNav === 'admin' ? ' is-active' : '' ?>" href="<?= url('admin.php') ?>">Admin</a>
            </div>
            <a class="nav-link nav-link--lab<?= $activeNav === 'lab' ? ' is-active' : '' ?>" href="<?= url('lab/') ?>">Lab Guide</a>
        </nav>
    </div>
</header>
<main id="main" class="page">
