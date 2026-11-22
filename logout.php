<?php
require_once __DIR__ . '/config.php';
session_start();
unset($_SESSION['user']);
unset($_SESSION['user_id']);
unset($_SESSION['role']);
unset($_SESSION['failopen']);
header('Location: index.php?message=You have logged out.');
exit;
