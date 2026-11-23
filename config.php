<?php
ini_set('session.use_only_cookies', '0');
ini_set('session.use_strict_mode', '0');
ini_set('session.cookie_httponly', '0');
ini_set('session.cookie_secure', '0');
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_OFF);

$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';
$dbName = getenv('DB_NAME') ?: 'faultline';

$conn = @mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
$dbError = $conn ? '' : mysqli_connect_error();

if (isset($_GET['db']) && $_GET['db'] === 'down') {
    if ($conn) {
        mysqli_close($conn);
    }
    $conn = false;
    $dbError = 'Database is unavailable';
}

define('APP_NAME', 'Faultline University Portal');
define('LAB_SECRET_KEY', '0123456789abcdef0123456789abcdef');
define('FLAG_DIR', rtrim(getenv('FLAG_DIR') ?: (PHP_OS_FAMILY === 'Windows' ? 'C:/flag' : '/flag'), '/\\'));

$envBase = getenv('BASE_URL');
if ($envBase !== false && $envBase !== '') {
    define('BASE_URL', rtrim($envBase, '/'));
} else {
    $appRoot = str_replace('\\', '/', (string) realpath(__DIR__));
    $docRoot = str_replace('\\', '/', (string) realpath($_SERVER['DOCUMENT_ROOT'] ?? ''));
    $detected = '';
    if ($docRoot !== '' && strpos($appRoot, $docRoot) === 0) {
        $detected = substr($appRoot, strlen($docRoot));
    }
    define('BASE_URL', rtrim($detected, '/'));
}

function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

function flag_value(string $name): string
{
    $path = FLAG_DIR . DIRECTORY_SEPARATOR . $name;
    if (is_file($path)) {
        return trim((string) file_get_contents($path));
    }
    return 'FLT{missing_' . $name . '}';
}

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        return false;
    }
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    if (strpos($script, '/api/') !== false) {
        return false;
    }
    echo '<div class="debug-block"><strong>Runtime detail</strong><br>';
    echo $errstr . '<br>' . $errfile . ':' . $errline . '<br>';
    echo flag_value('errors.txt');
    echo '</div>';
    return true;
});
