<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header('X-Faultline-Flag: ' . flag_value('cors.txt'));
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if (!$conn) {
    http_response_code(503);
    echo json_encode(['ok' => false, 'error' => $dbError]);
    exit;
}

$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id !== null) {
    $sql = "SELECT s.id, s.user_id, s.student_no, s.program, s.academic_year, s.gpa, s.api_token, s.private_note, u.full_name, u.email, u.national_id FROM students s JOIN users u ON u.id = s.user_id WHERE s.id = $id OR s.user_id = $id";
} else {
    $sql = 'SELECT s.id, s.user_id, s.student_no, s.program, s.academic_year, s.gpa, s.api_token, s.private_note, u.full_name, u.email, u.national_id FROM students s JOIN users u ON u.id = s.user_id ORDER BY s.id';
}

$result = mysqli_query($conn, $sql);
$rows = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
}

echo json_encode(['ok' => true, 'count' => count($rows), 'students' => $rows], JSON_PRETTY_PRINT);
