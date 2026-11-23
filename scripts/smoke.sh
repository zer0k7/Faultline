#!/bin/sh
set -eu

BASE="${LAB_URL:-http://127.0.0.1:8080}"
PASS=0
FAIL=0

check() {
    method="$1"
    path="$2"
    expect="$3"
    data="${4:-}"

    if [ "$method" = "POST" ]; then
        code=$(curl -s -o /dev/null -w "%{http_code}" -X POST --data "$data" "$BASE$path" || echo 000)
    else
        code=$(curl -s -o /dev/null -w "%{http_code}" "$BASE$path" || echo 000)
    fi

    case " $expect " in
        *" $code "*)
            PASS=$((PASS + 1))
            printf 'ok   %-4s %-42s %s\n' "$method" "$path" "$code"
            ;;
        *)
            FAIL=$((FAIL + 1))
            printf 'FAIL %-4s %-42s %s expected %s\n' "$method" "$path" "$code" "$expect"
            ;;
    esac
}

check_body() {
    path="$1"
    needle="$2"
    body=$(curl -s "$BASE$path" || echo "")
    case "$body" in
        *"$needle"*)
            PASS=$((PASS + 1))
            printf 'ok   BODY %-42s contains %s\n' "$path" "$needle"
            ;;
        *)
            FAIL=$((FAIL + 1))
            printf 'FAIL BODY %-42s missing %s\n' "$path" "$needle"
            ;;
    esac
}

check GET  / "200"
check GET  /index.php "200"
check GET  /login.php "200"
check POST /login.php "200" "username=nobody&password=nope"
check GET  /register.php "200"
check GET  /dashboard.php "200"
check GET  /profile.php "200"
check GET  /profile.php?id=5 "200"
check GET  /transcript.php?id=5 "200"
check GET  /courses.php "200"
check GET  /course.php?code=CS101 "200"
check GET  /faculty.php "200"
check GET  /news.php "200"
check GET  /feedback.php "200"
check GET  /contact.php "200"
check GET  /upload.php "200"
check GET  /admin.php "200"
check GET  /fees.php "200"
check GET  /account.php "200"
check GET  /prefs.php "200"
check GET  /idcard.php?id=5 "200"
check GET  /reset.php "200"
check GET  /logout.php "302"
check GET  /pages/view.php?page=help.php "200"
check GET  /pages/view.php?page=about.php "200"
check GET  /admin/diagnostics.php "200"
check POST /admin/diagnostics.php "200" "host=localhost"
check GET  /admin/import-notice.php "200"
check POST /admin/import-notice.php "200" "url="
check GET  /admin/import-courses.php "200"
check POST /admin/import-courses.php "200" "xml=<catalog><course><code>CS101</code><title>Sample</title></course></catalog>"
check GET  /admin/theme-install.php "200"
check POST /admin/theme-install.php "200" "url="
check GET  /admin/update.php "200"
check POST /admin/update.php "200" ""
check GET  /admin/logs.php "200"
check GET  /admin/bulk.php "200"
check GET  /admin/bulk.php?rows=8 "200"
check GET  /api/students.php "200"
check GET  /api/students.php?id=3 "200"
check GET  /api/login.php "401"
check POST /api/login.php "401" "username=nobody&password=nope"
check GET  /api/grades.php "401"
check GET  /lab/ "200"
check GET  /lab/check.php "405"
check POST /lab/check.php "200" "module=a01-idor&flag=FLT%7Bnope%7D"
check GET  /cdn.php "200"
check GET  /phpinfo.php "200"
check GET  /backup/ "200"
check GET  /backup/flag.txt "200"
check GET  /logs/app.log "200"
check GET  /.env "200"

check_body /api/students.php '"count": 12'
check_body /lab/ 'Faultline Lab Guide'
check_body /.env 'FLT{exposed_env_9a4c}'
check_body /logs/app.log 'FLT{log_webroot_5c9e}'

printf '\n%s passed, %s failed\n' "$PASS" "$FAIL"
if [ "$FAIL" -ne 0 ]; then
    exit 1
fi
