<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function b64url_decode(string $value): string
{
    return base64_decode(strtr($value, '-_', '+/'));
}

function b64url_encode(string $value): string
{
    return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
}

$authorization = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
if ($authorization === '' && function_exists('getallheaders')) {
    $headers = getallheaders();
    $authorization = $headers['Authorization'] ?? $headers['authorization'] ?? '';
}

$token = trim(preg_replace('/^Bearer\s+/i', '', $authorization));
$parts = explode('.', $token);

if (count($parts) !== 3) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'token required']);
    exit;
}

$header = json_decode(b64url_decode($parts[0]), true);
$payload = json_decode(b64url_decode($parts[1]), true);

if (!is_array($header) || !is_array($payload)) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'malformed token']);
    exit;
}

$algorithm = $header['alg'] ?? '';
$valid = false;

if ($algorithm === 'none') {
    $valid = true;
} elseif ($algorithm === 'HS256') {
    $expected = b64url_encode(hash_hmac('sha256', $parts[0] . '.' . $parts[1], 'faultline', true));
    $valid = hash_equals($expected, $parts[2]);
}

if (!$valid) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'invalid signature']);
    exit;
}

if (!$conn) {
    http_response_code(503);
    echo json_encode(['ok' => false, 'error' => $dbError]);
    exit;
}

$role = $payload['role'] ?? 'student';
$subject = $payload['sub'] ?? '';

if ($role === 'admin') {
    $result = mysqli_query($conn, 'SELECT g.*, c.code, c.title, u.full_name FROM grades g JOIN courses c ON c.id = g.course_id JOIN students s ON s.id = g.student_id JOIN users u ON u.id = s.user_id ORDER BY g.student_id, c.code');
    $rows = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    echo json_encode([
        'ok' => true,
        'role' => 'admin',
        'count' => count($rows),
        'grades' => $rows,
        'flag' => flag_value('jwt.txt'),
    ], JSON_PRETTY_PRINT);
    exit;
}

$result = mysqli_query($conn, "SELECT g.*, c.code, c.title FROM grades g JOIN courses c ON c.id = g.course_id JOIN students s ON s.id = g.student_id JOIN users u ON u.id = s.user_id WHERE u.username='$subject' ORDER BY c.code");
$rows = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
}

echo json_encode(['ok' => true, 'role' => $role, 'count' => count($rows), 'grades' => $rows], JSON_PRETTY_PRINT);
