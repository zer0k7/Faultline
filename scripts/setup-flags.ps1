$target = if ($env:FLAG_DIR) { $env:FLAG_DIR } else { "C:\flag" }
New-Item -ItemType Directory -Force -Path $target | Out-Null
$flags = [ordered]@{
  "cmd.txt"       = "FLT{command_injection_8c4f}"
  "lfi.txt"       = "FLT{local_file_include_2f7b}"
  "upload.txt"    = "FLT{unrestricted_upload_9d5a}"
  "unserialize.txt" = "FLT{php_object_inject_3a6d}"
  "xxe.txt"       = "FLT{xxe_entity_read_2b8f}"
  "reset.txt"     = "FLT{predictable_reset_1d6c}"
  "xss.txt"       = "FLT{cookie_steal_xss_5a1d}"
  "logic.txt"     = "FLT{coupon_reuse_6b2e}"
  "jwt.txt"       = "FLT{alg_none_jwt_4f8c}"
  "session.txt"   = "FLT{remember_me_admin_7e2a}"
  "logxss.txt"    = "FLT{log_viewer_xss_2d4a}"
  "failopen.txt"  = "FLT{fail_open_auth_6e8b}"
  "errors.txt"    = "FLT{verbose_error_path_1b7c}"
  "cors.txt"      = "FLT{cors_wildcard_6e3d}"
}
foreach ($name in $flags.Keys) {
  Set-Content -LiteralPath (Join-Path $target $name) -Value $flags[$name] -Encoding utf8
}
Write-Output "Flag files written to $target"
