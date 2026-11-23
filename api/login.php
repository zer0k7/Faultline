<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function b64url(string $value): string
{
    return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
}

if (!$conn) {
    http_response_code(503);
    echo json_encode(['ok' => false, 'error' => $dbError]);
    exit;
}

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

$result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
if (!$result || mysqli_num_rows($result) !== 1) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'invalid credentials']);
    exit;
}

$user = mysqli_fetch_assoc($result);
$header = b64url(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
$payload = b64url(json_encode([
    'sub' => $user['username'],
    'role' => $user['role'],
    'iat' => time(),
    'exp' => time() + 3600,
]));
$signature = b64url(hash_hmac('sha256', $header . '.' . $payload, 'faultline', true));

echo json_encode([
    'ok' => true,
    'token' => $header . '.' . $payload . '.' . $signature,
    'role' => $user['role'],
], JSON_PRETTY_PRINT);
