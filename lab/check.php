<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'method']);
    exit;
}

$module = isset($_POST['module']) ? trim((string) $_POST['module']) : '';
$flag = isset($_POST['flag']) ? trim((string) $_POST['flag']) : '';

if ($module === '' || $flag === '' || !$conn) {
    echo json_encode(['ok' => false]);
    exit;
}

$stmt = mysqli_prepare($conn, 'SELECT flag_hash FROM flags WHERE module_key = ?');
mysqli_stmt_bind_param($stmt, 's', $module);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($row && hash_equals($row['flag_hash'], hash('sha256', $flag))) {
    echo json_encode(['ok' => true]);
} else {
    echo json_encode(['ok' => false]);
}
