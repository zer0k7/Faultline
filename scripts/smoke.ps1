$ErrorActionPreference = "Stop"
$base = if ($env:LAB_URL) { $env:LAB_URL } else { "http://127.0.0.1:8080" }
$pass = 0
$fail = 0

function Check-Status {
    param(
        [string]$Method,
        [string]$Path,
        [int[]]$Expected,
        [string]$Body = ""
    )

    try {
        if ($Method -eq "POST") {
            $response = Invoke-WebRequest -Uri "$base$Path" -Method Post -Body $Body -ContentType "application/x-www-form-urlencoded" -SkipHttpErrorCheck -MaximumRedirection 0
        } else {
            $response = Invoke-WebRequest -Uri "$base$Path" -Method Get -SkipHttpErrorCheck -MaximumRedirection 0
        }
        $code = [int]$response.StatusCode
    } catch {
        $code = 0
    }

    if ($Expected -contains $code) {
        $script:pass += 1
        Write-Output ("ok   {0,-4} {1,-42} {2}" -f $Method, $Path, $code)
    } else {
        $script:fail += 1
        Write-Output ("FAIL {0,-4} {1,-42} {2} expected {3}" -f $Method, $Path, $code, ($Expected -join ","))
    }
}

function Check-Body {
    param(
        [string]$Path,
        [string]$Needle
    )

    try {
        $response = Invoke-WebRequest -Uri "$base$Path" -Method Get -SkipHttpErrorCheck
        $body = [string]$response.Content
    } catch {
        $body = ""
    }

    if ($body.Contains($Needle)) {
        $script:pass += 1
        Write-Output ("ok   BODY {0,-42} contains {1}" -f $Path, $Needle)
    } else {
        $script:fail += 1
        Write-Output ("FAIL BODY {0,-42} missing {1}" -f $Path, $Needle)
    }
}

Check-Status "GET" "/" @(200)
Check-Status "GET" "/index.php" @(200)
Check-Status "GET" "/login.php" @(200)
Check-Status "POST" "/login.php" @(200) "username=nobody&password=nope"
Check-Status "GET" "/register.php" @(200)
Check-Status "GET" "/dashboard.php" @(200)
Check-Status "GET" "/profile.php" @(200)
Check-Status "GET" "/profile.php?id=5" @(200)
Check-Status "GET" "/transcript.php?id=5" @(200)
Check-Status "GET" "/courses.php" @(200)
Check-Status "GET" "/course.php?code=CS101" @(200)
Check-Status "GET" "/faculty.php" @(200)
Check-Status "GET" "/news.php" @(200)
Check-Status "GET" "/feedback.php" @(200)
Check-Status "GET" "/contact.php" @(200)
Check-Status "GET" "/upload.php" @(200)
Check-Status "GET" "/admin.php" @(200)
Check-Status "GET" "/fees.php" @(200)
Check-Status "GET" "/account.php" @(200)
Check-Status "GET" "/prefs.php" @(200)
Check-Status "GET" "/idcard.php?id=5" @(200)
Check-Status "GET" "/reset.php" @(200)
Check-Status "GET" "/logout.php" @(302)
Check-Status "GET" "/pages/view.php?page=help.php" @(200)
Check-Status "GET" "/pages/view.php?page=about.php" @(200)
Check-Status "GET" "/admin/diagnostics.php" @(200)
Check-Status "POST" "/admin/diagnostics.php" @(200) "host=localhost"
Check-Status "GET" "/admin/import-notice.php" @(200)
Check-Status "POST" "/admin/import-notice.php" @(200) "url="
Check-Status "GET" "/admin/import-courses.php" @(200)
Check-Status "POST" "/admin/import-courses.php" @(200) "xml=<catalog><course><code>CS101</code><title>Sample</title></course></catalog>"
Check-Status "GET" "/admin/theme-install.php" @(200)
Check-Status "POST" "/admin/theme-install.php" @(200) "url="
Check-Status "GET" "/admin/update.php" @(200)
Check-Status "POST" "/admin/update.php" @(200) ""
Check-Status "GET" "/admin/logs.php" @(200)
Check-Status "GET" "/admin/bulk.php" @(200)
Check-Status "GET" "/admin/bulk.php?rows=8" @(200)
Check-Status "GET" "/api/students.php" @(200)
Check-Status "GET" "/api/students.php?id=3" @(200)
Check-Status "GET" "/api/login.php" @(401)
Check-Status "POST" "/api/login.php" @(401) "username=nobody&password=nope"
Check-Status "GET" "/api/grades.php" @(401)
Check-Status "GET" "/lab/" @(200)
Check-Status "GET" "/lab/check.php" @(405)
Check-Status "POST" "/lab/check.php" @(200) "module=a01-idor&flag=FLT%7Bnope%7D"
Check-Status "GET" "/cdn.php" @(200)
Check-Status "GET" "/phpinfo.php" @(200)
Check-Status "GET" "/backup/" @(200)
Check-Status "GET" "/backup/flag.txt" @(200)
Check-Status "GET" "/logs/app.log" @(200)
Check-Status "GET" "/.env" @(200)

Check-Body "/api/students.php" '"count": 12'
Check-Body "/lab/" "Faultline Lab Guide"
Check-Body "/.env" "FLT{exposed_env_9a4c}"
Check-Body "/logs/app.log" "FLT{log_webroot_5c9e}"

Write-Output ""
Write-Output ("{0} passed, {1} failed" -f $pass, $fail)
if ($fail -ne 0) {
    exit 1
}
