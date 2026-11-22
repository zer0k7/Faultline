import io
import json
import zipfile
from http.server import BaseHTTPRequestHandler, ThreadingHTTPServer

INSTALLER_FLAG = "FLT{theme_zip_install_4c7a}"
SUPPLY_FLAG = "FLT{cdn_no_sri_8f2b}"
SSRF_FLAG = "FLT{ssrf_internal_meta_5d1e}"
UPDATE_FLAG = "FLT{unsigned_update_8b1f}"


def theme_zip():
    buffer = io.BytesIO()
    with zipfile.ZipFile(buffer, "w", zipfile.ZIP_DEFLATED) as archive:
        archive.writestr("theme.json", json.dumps({
            "name": "Campus Slate",
            "version": "1.4.0",
            "author": "Northwind Themes",
            "entry": "style.css",
        }, indent=2))
        archive.writestr("style.css", "body{--theme-accent:#e0592a;}\n.slate-card{border:1px solid #d7dce4;}\n")
        archive.writestr("readme.txt", "Campus Slate theme package. Extract into the themes directory.\n")
        archive.writestr("flag.txt", INSTALLER_FLAG + "\n")
    return buffer.getvalue()


class Handler(BaseHTTPRequestHandler):
    server_version = "FaultlineInternal/1.0"

    def send_body(self, status, body, content_type="text/plain; charset=utf-8", extra=None):
        if isinstance(body, str):
            body = body.encode("utf-8")
        self.send_response(status)
        self.send_header("Content-Type", content_type)
        self.send_header("Content-Length", str(len(body)))
        if extra:
            for key, value in extra.items():
                self.send_header(key, value)
        self.end_headers()
        self.wfile.write(body)

    def do_GET(self):
        path = self.path.split("?", 1)[0]

        if path == "/":
            html = """<!doctype html>
<html lang="en"><head><meta charset="utf-8"><title>Faultline Internal</title>
<style>body{font-family:system-ui,sans-serif;background:#0f1b2d;color:#e9edf3;margin:0;padding:48px}
main{max-width:720px;margin:0 auto}h1{font-size:28px}a{color:#e0592a;display:block;margin:10px 0}
code{background:#1b2a41;padding:2px 6px;border-radius:4px}</style></head>
<body><main><h1>Faultline Internal Service</h1>
<p>Private service for the training lab. Not published to the host.</p>
<a href="/metadata/instance">/metadata/instance</a>
<a href="/internal/employees">/internal/employees</a>
<a href="/internal/config">/internal/config</a>
<a href="/packages/theme.zip">/packages/theme.zip</a>
<a href="/cdn/widget.js">/cdn/widget.js</a>
<a href="/updates/latest.json">/updates/latest.json</a>
<a href="/health">/health</a>
</main></body></html>"""
            self.send_body(200, html, "text/html; charset=utf-8")
            return

        if path == "/health":
            self.send_body(200, json.dumps({"status": "ok", "service": "internal"}), "application/json")
            return

        if path == "/metadata/instance":
            payload = {
                "instance_id": "i-faultline-01",
                "role": "lab-internal",
                "region": "local",
                "image": "faultline-internal",
                "credentials": {
                    "access_key": "AKIA-FAULTLINE-DEMO",
                    "secret_key": "demo-only-secret",
                    "session_token": SSRF_FLAG,
                },
            }
            self.send_body(200, json.dumps(payload, indent=2), "application/json")
            return

        if path == "/metadata/iam":
            self.send_body(200, json.dumps({
                "account": "faultline-lab",
                "role": "internal-reader",
                "permissions": ["internal:read", "packages:read"],
                "token": SSRF_FLAG,
            }, indent=2), "application/json")
            return

        if path == "/internal/employees":
            self.send_body(200, json.dumps([
                {"id": 1, "name": "R. Calder", "team": "Platform", "badge": "INT-1001"},
                {"id": 2, "name": "S. Imani", "team": "Records", "badge": "INT-1002"},
                {"id": 3, "name": "T. Voss", "team": "Network", "badge": "INT-1003"},
            ], indent=2), "application/json")
            return

        if path == "/internal/config":
            self.send_body(200, json.dumps({
                "environment": "internal",
                "db_host": "db",
                "db_name": "faultline",
                "mail_relay": "smtp.internal",
                "note": "Demo values only.",
            }, indent=2), "application/json")
            return

        if path == "/packages/theme.zip":
            self.send_body(200, theme_zip(), "application/zip", {
                "Content-Disposition": "attachment; filename=theme.zip"
            })
            return

        if path == "/cdn/widget.js":
            script = """(function () {
  window.FLT_CDN_FLAG = "%s";
  function paint() {
    var el = document.getElementById("cdn-widget");
    if (el) {
      el.textContent = "Widget build 8f2b ready. " + window.FLT_CDN_FLAG;
    }
  }
  if (document.readyState !== "loading") { paint(); }
  else { document.addEventListener("DOMContentLoaded", paint); }
})();
""" % SUPPLY_FLAG
            self.send_body(200, script, "application/javascript; charset=utf-8")
            return

        if path == "/updates/latest.json":
            self.send_body(200, json.dumps({
                "version": "9.4.1",
                "channel": "stable",
                "notes": "Theme loader patch and record index rebuild.",
                "flag": UPDATE_FLAG,
                "sha256": "0000000000000000000000000000000000000000000000000000000000000000",
            }, indent=2), "application/json")
            return

        self.send_body(404, "Not found\n")


if __name__ == "__main__":
    ThreadingHTTPServer(("0.0.0.0", 80), Handler).serve_forever()
