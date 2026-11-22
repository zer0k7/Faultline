<?php
require_once __DIR__ . '/../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle = $pageTitle ?? APP_NAME;
$activeNav = $activeNav ?? '';
$navUser = $_SESSION['user'] ?? '';
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
<div class="lab-banner" role="note">Intentionally vulnerable training application. Run locally only. Never expose it to a network.</div>
<header class="site-header">
    <div class="header-inner">
        <a class="brand" href="<?= url('index.php') ?>">
            <img src="<?= url('assets/logo.svg') ?>" alt="" width="38" height="38">
            <span class="brand-text">
                <strong>Faultline</strong>
                <small>University Portal</small>
            </span>
        </a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-nav">Menu</button>
        <nav id="primary-nav" class="primary-nav" aria-label="Primary">
            <a class="nav-link<?= $activeNav === 'home' ? ' is-active' : '' ?>" href="<?= url('index.php') ?>">Home</a>
            <a class="nav-link<?= $activeNav === 'courses' ? ' is-active' : '' ?>" href="<?= url('courses.php') ?>">Courses</a>
            <a class="nav-link<?= $activeNav === 'faculty' ? ' is-active' : '' ?>" href="<?= url('faculty.php') ?>">Faculty</a>
            <a class="nav-link<?= $activeNav === 'news' ? ' is-active' : '' ?>" href="<?= url('news.php') ?>">News</a>
            <a class="nav-link<?= $activeNav === 'feedback' ? ' is-active' : '' ?>" href="<?= url('feedback.php') ?>">Feedback</a>
            <a class="nav-link<?= $activeNav === 'contact' ? ' is-active' : '' ?>" href="<?= url('contact.php') ?>">Contact</a>
            <a class="nav-link<?= $activeNav === 'dashboard' ? ' is-active' : '' ?>" href="<?= url('dashboard.php') ?>">Dashboard</a>
            <a class="nav-link<?= $activeNav === 'profile' ? ' is-active' : '' ?>" href="<?= url('profile.php') ?>">Profile</a>
            <a class="nav-link<?= $activeNav === 'upload' ? ' is-active' : '' ?>" href="<?= url('upload.php') ?>">Upload</a>
            <a class="nav-link<?= $activeNav === 'admin' ? ' is-active' : '' ?>" href="<?= url('admin.php') ?>">Admin</a>
            <a class="nav-link<?= $activeNav === 'lab' ? ' is-active' : '' ?>" href="<?= url('lab/') ?>">Lab Guide</a>
        </nav>
        <div class="header-account">
            <?php if ($navUser !== ''): ?>
                <span class="signed-in">Signed in as <strong><?= $navUser ?></strong></span>
                <a class="btn btn-ghost btn-sm" href="<?= url('logout.php') ?>">Log out</a>
            <?php else: ?>
                <a class="btn btn-ghost btn-sm" href="<?= url('login.php') ?>">Log in</a>
                <a class="btn btn-primary btn-sm" href="<?= url('register.php') ?>">Register</a>
            <?php endif; ?>
        </div>
    </div>
</header>
<main id="main" class="page">
