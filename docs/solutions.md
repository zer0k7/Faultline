# Faultline Solutions

Spoiler warning. This file explains every deliberate weakness in the lab. Read it only after you have tried the modules, or when you are preparing a training session.

Faultline is an intentionally vulnerable application. The shipped code stays vulnerable. This document describes the flaws and the fix ideas in prose. It does not ship patched module code.

## How to use this file

Each module lists the location, the flaw and vulnerability class, the OWASP Top 10:2025 category, the CWE, the place where the flag lives, and a fix idea. The Lab Guide at `/lab/` holds the player facing hints. This file holds the full explanation.

Flags use the form `FLT{...}`. Flag values are not repeated here. The flag location is enough to confirm a finding.

## Coverage summary

| Category | Modules | Difficulty range |
| --- | --- | --- |
| A01:2025 Broken Access Control | 3 | Easy to Hard |
| A02:2025 Security Misconfiguration | 3 | Easy to Medium |
| A03:2025 Software Supply Chain Failures | 2 | Easy to Medium |
| A04:2025 Cryptographic Failures | 2 | Easy to Medium |
| A05:2025 Injection | 4 | Medium to Hard |
| A06:2025 Insecure Design | 2 | Medium |
| A07:2025 Authentication Failures | 2 | Medium to Hard |
| A08:2025 Software or Data Integrity Failures | 2 | Medium to Hard |
| A09:2025 Security Logging and Alerting Failures | 2 | Easy to Medium |
| A10:2025 Mishandling of Exceptional Conditions | 2 | Easy to Medium |

## A01:2025 Broken Access Control

### A01.1 Profile Record Access

- Module key: `a01-idor`
- Location: `profile.php`, `transcript.php`, `idcard.php`
- Flaw and class: The pages accept an object identifier from the query string and load the record without checking who owns it. This is an insecure direct object reference. The profile page also exposes the registry note, which is where the flag sits.
- OWASP 2025: A01 Broken Access Control
- CWE: CWE-639 Authorization Bypass Through User-Controlled Key
- Flag location: The private note of another student record.
- Fix idea: Take the subject from the authenticated session. If an identifier must be accepted, check that the current user owns the record or holds the required role. Prefer indirect references that map to the current user. Apply the same check to transcripts and ID cards.

### A01.2 Administrative Actions

- Module key: `a01-admin`
- Location: `admin.php`
- Flaw and class: The administration page has no authentication or role check. Account deletion is a GET request with no confirmation token. The registry notes panel returns administrative data to any visitor. This is a missing function level access control flaw with a state change over GET.
- OWASP 2025: A01 Broken Access Control
- CWE: CWE-862 Missing Authorization, CWE-352 Cross-Site Request Forgery
- Flag location: A row in the administrative notes panel.
- Fix idea: Require an authenticated administrator session for every administrative route. Enforce the role on the server for each action. Move deletion to POST with a CSRF token. Do not return administrative data to unauthenticated callers. Log the action with the actor identity.

### A01.3 Notice Import

- Module key: `a01-ssrf`
- Location: `admin/import-notice.php`
- Flaw and class: The import form passes a user supplied URL to `file_get_contents`. The server fetches the URL from inside the container, so the request can reach the private internal service and its metadata endpoint. This is server side request forgery.
- OWASP 2025: A01 Broken Access Control
- CWE: CWE-918 Server-Side Request Forgery
- Flag location: The internal metadata response.
- Fix idea: Do not fetch arbitrary URLs. Use an allowlist of schemes and hosts. Resolve the host and block private, loopback and link local ranges. Prefer a dedicated egress proxy with a strict policy. Require authorization for the import feature.

## A02:2025 Security Misconfiguration

### A02.1 Exposed Files

- Module key: `a02-exposure`
- Location: `.env`, `backup/`, `logs/`, `phpinfo.php`, `uploads/`, `themes/`
- Flaw and class: Operational material sits inside the web root. The environment file, backup folder, log file and PHP information page are all reachable. Directory listing is enabled in the backup, upload and theme folders. This is sensitive information exposure caused by unsafe deployment defaults.
- OWASP 2025: A02 Security Misconfiguration
- CWE: CWE-200 Exposure of Sensitive Information, CWE-538 Insertion of Sensitive Information into Externally-Accessible File or Directory, CWE-548 Exposure of Information Through Directory Listing
- Flag location: The environment file and the backup folder.
- Fix idea: Keep secrets outside the document root. Remove the backup and PHP information pages from production. Disable directory indexes. Block dotfiles and backup extensions at the web server. Grant the web user only the permissions it needs.

### A02.2 XML Course Import

- Module key: `a02-xxe`
- Location: `admin/import-courses.php`
- Flaw and class: The XML parser is called with external entity and DTD loading enabled. A document can declare an entity that reads a local file and place the value in a field that the page renders. This is XML external entity injection.
- OWASP 2025: A02 Security Misconfiguration
- CWE: CWE-611 Improper Restriction of XML External Entity Reference
- Flag location: The local flag file read through the entity.
- Fix idea: Disable external entity and DTD loading. Use `LIBXML_NONET` and avoid `LIBXML_NOENT`. Reject documents with a DTD when the format does not need one. Prefer a simple JSON format. Run the parser with a small input limit.

### A02.3 API CORS and Headers

- Module key: `a02-cors`
- Location: `api/students.php`, `api/login.php`, `api/grades.php`
- Flaw and class: The API sends a wildcard origin together with credentials, reflects requests from any origin, and omits common security headers. A custom response header carries the flag when an origin is present. This is an overly permissive cross origin policy combined with missing hardening headers.
- OWASP 2025: A02 Security Misconfiguration
- CWE: CWE-942 Permissive Cross-domain Policy with Untrusted Domains, CWE-693 Protection Mechanism Failure
- Flag location: The custom response header returned when an `Origin` header is sent.
- Fix idea: Use an explicit origin allowlist. Never combine a wildcard origin with credentials. Send only the headers the client needs. Add a content security policy, frame restrictions, a referrer policy, a content type options header and transport security. Remove debug headers from production responses.

## A03:2025 Software Supply Chain Failures

### A03.1 Theme Installer

- Module key: `a03-installer`
- Location: `admin/theme-install.php`
- Flaw and class: The installer downloads a ZIP from a user supplied URL, extracts it without a checksum or signature, and publishes the extracted files under the web root. The package can contain executable content or a path traversal entry. This is an untrusted package installation flaw.
- OWASP 2025: A03 Software Supply Chain Failures
- CWE: CWE-494 Download of Code Without Integrity Check, CWE-829 Inclusion of Functionality from Untrusted Control Sphere
- Flag location: A file inside the internal theme package.
- Fix idea: Install only signed packages from an allowlisted source. Verify a signature and a checksum before extraction. Extract into a staging directory and validate every path. Reject executable file types. Publish themes from a separate origin without script execution. Keep an inventory of installed packages.

### A03.2 Third Party Widget

- Module key: `a03-supply`
- Location: `news.php`, `cdn.php`, `assets/js/widget.js`, `composer.json`, `.github/workflows/ci.yml`
- Flaw and class: The news page loads a third party script without a subresource integrity attribute. The local proxy fetches a widget from the internal service and serves it with the flag in a global variable. The dependency file uses wildcard versions and has no lock file, and it pins a Guzzle release with a public advisory. The CI workflow uses an action tag instead of a commit hash and checks out pull request code in a `pull_request_target` job that has access to a deployment secret. This is a supply chain integrity failure.
- OWASP 2025: A03 Software Supply Chain Failures
- CWE: CWE-1104 Use of Unmaintained Third Party Components, CWE-829 Inclusion of Functionality from Untrusted Control Sphere, CWE-1357 Reliance on Insufficiently Trustworthy Component
- Flag location: The global value exposed by the widget script.
- Fix idea: Add subresource integrity hashes to third party scripts and serve them over HTTPS. Pin dependencies to exact versions and commit a lock file. Track advisories and update promptly. Pin CI actions to commit hashes. Do not expose secrets to pull request code. Generate and review an SBOM. The pinned Guzzle release in this lab is affected by CVE-2022-31090, fixed in 7.4.5.

## A04:2025 Cryptographic Failures

### A04.1 Password Reset Tokens

- Module key: `a04-reset`
- Location: `reset.php`
- Flaw and class: The reset token is the MD5 hash of the username. Anyone who knows the username can compute the token. The token has no expiry and no single use state. The security question flow accepts a guessable answer. The reference number uses `rand`. This is a predictable token and weak recovery design.
- OWASP 2025: A04 Cryptographic Failures
- CWE: CWE-330 Use of Insufficiently Random Values, CWE-640 Weak Password Recovery Mechanism for Forgotten Password, CWE-327 Use of a Broken or Risky Cryptographic Algorithm
- Flag location: The reset page after a valid token is supplied.
- Fix idea: Generate a token with a cryptographically secure random source. Store only a hash of the token with an expiry and a single use flag. Invalidate the token after use and on password change. Rate limit reset requests. Replace security questions with a verified out of band channel. Use a CSPRNG for any reference value.

### A04.2 Student ID Card Token

- Module key: `a04-idcard`
- Location: `idcard.php`, `db/seed.sql`
- Flaw and class: The page presents a base64 value as if it were encrypted. The current token uses AES in ECB mode with a hard coded key. The database also stores plaintext and unsalted MD5 passwords in other tables. Sensitive personal data is stored without protection. This is a set of cryptographic failures.
- OWASP 2025: A04 Cryptographic Failures
- CWE: CWE-327 Use of a Broken or Risky Cryptographic Algorithm, CWE-321 Use of Hard-coded Cryptographic Key, CWE-256 Unprotected Storage of Credentials
- Flag location: The base64 encoded card payload.
- Fix idea: Use authenticated encryption such as AES-GCM with a key from a secret manager. Never treat base64 as encryption. Do not store sensitive fields in a readable payload. Hash passwords with Argon2id or bcrypt and a per user salt. Rotate keys and record key versions.

## A05:2025 Injection

### A05.1 Query Injection

- Module key: `a05-sqli`
- Location: `login.php`, `register.php`, `news.php`, `courses.php`, `course.php`, `faculty.php`, `profile.php`, `reset.php`, `api/login.php`, `api/students.php`, `fees.php`
- Flaw and class: User input is concatenated into SQL strings. The login form allows authentication bypass. The news search supports a UNION query that reads the secrets table. The course lookup supports boolean and time based blind tests. The profile biography is stored first and then reused in a second query, which is a second order injection. The API and fee pages repeat the same pattern. This is SQL injection.
- OWASP 2025: A05 Injection
- CWE: CWE-89 Improper Neutralization of Special Elements used in an SQL Command
- Flag location: The vault value in the secrets table, reachable through the news search.
- Fix idea: Use prepared statements with bound parameters for every query. Do not build SQL from strings. Give the application a least privilege database user. Validate input types and lengths. Use an ORM or query builder with parameter binding. Add tests that send quote characters and confirm the query still treats them as data.

### A05.2 Cross Site Scripting

- Module key: `a05-xss`
- Location: `news.php`, `feedback.php`, `contact.php`, `courses.php`, `faculty.php`, `index.php`, `assets/js/app.js`
- Flaw and class: The news search reflects input without encoding. Feedback and contact messages are stored and rendered as HTML. The home page writes the URL hash into `innerHTML`, which gives a DOM based path. The page sets a readable cookie that carries the flag. This is reflected, stored and DOM based cross site scripting.
- OWASP 2025: A05 Injection
- CWE: CWE-79 Improper Neutralization of Input During Web Page Generation
- Flag location: The readable `xss_canary` cookie.
- Fix idea: Encode output for the context where it is used. Prefer text nodes over HTML. Avoid `innerHTML` with untrusted data. Sanitize any rich text with a maintained allowlist library. Add a content security policy. Mark session cookies HttpOnly and SameSite. Do not put secrets in script readable cookies.

### A05.3 Command Injection

- Module key: `a05-rce`
- Location: `admin/diagnostics.php`
- Flaw and class: The diagnostics tool concatenates a host value into a shell command. A separator or substitution sequence runs an additional command inside the container. This is operating system command injection.
- OWASP 2025: A05 Injection
- CWE: CWE-78 Improper Neutralization of Special Elements used in an OS Command
- Flag location: A file in the container flag directory.
- Fix idea: Avoid the shell. Use a resolver library or a fixed argument list. If a command is unavoidable, use `escapeshellarg` and a strict allowlist for the host value. Run the tool with minimal privileges. Keep the container isolated and read only where possible. Remove the tool from production builds.

### A05.4 Local File Inclusion

- Module key: `a05-lfi`
- Location: `pages/view.php`
- Flaw and class: The page loader includes a path from the query string. Traversal sequences escape the pages directory and load a file outside the web root. This is local file inclusion.
- OWASP 2025: A05 Injection
- CWE: CWE-98 Improper Control of Filename for Include/Require Statement, CWE-22 Improper Limitation of a Pathname to a Restricted Directory
- Flag location: A file outside the web root, reached through traversal.
- Fix idea: Map a small set of page keys to fixed files. Do not include a user supplied path. Resolve the real path and confirm it stays inside the allowed directory. Disable remote includes. Run with an open basedir restriction where the platform supports it.

## A06:2025 Insecure Design

### A06.1 Fee Logic

- Module key: `a06-logic`
- Location: `fees.php`, `courses.php`
- Flaw and class: The fee page trusts the posted amount, so a client can pay a negative value or a value unrelated to the charge. Coupons are counted but not limited per account, so the same coupon can be reused. The payment insert and the fee status update are separate statements, so an error leaves an inconsistent state. The course enrollment trusts a posted capacity, which bypasses the seat limit. There is no rate limit or lockout on login and reset. These are business logic and workflow design flaws.
- OWASP 2025: A06 Insecure Design
- CWE: CWE-840 Business Logic Errors, CWE-602 Client-Side Enforcement of Server-Side Security, CWE-799 Improper Control of Interaction Frequency
- Flag location: The fee page after a coupon is reused.
- Fix idea: Compute prices and totals on the server from stored records. Reject non-positive amounts. Track coupon use per account and enforce the limit in the same transaction as the payment. Use a database transaction for the payment and the fee status. Enforce the seat limit in a transaction. Add rate limits and lockout to authentication and recovery flows. Write misuse cases for each business rule.

### A06.2 File Upload

- Module key: `a06-upload`
- Location: `upload.php`, `uploads/`
- Flaw and class: The upload handler accepts any file, keeps the original name, and writes into a directory that the web server executes. A PHP file can be uploaded and requested, which gives code execution inside the container. This is unrestricted upload of a dangerous file type.
- OWASP 2025: A06 Insecure Design. The OWASP 2025 A06 page lists CWE-434 in its mapped CWE list, so the upload module is placed here and the mapping is noted.
- CWE: CWE-434 Unrestricted Upload of File with Dangerous Type
- Flag location: A file in the container flag directory, read through the uploaded code.
- Fix idea: Allowlist the file types the feature needs and verify the content, not only the extension. Generate a random name and store the file outside the web root. Serve downloads through a handler that sets a safe content type and disposition. Disable script execution in the upload directory. Enforce size limits and scan files where appropriate.

## A07:2025 Authentication Failures

### A07.1 API Token Forgery

- Module key: `a07-jwt`
- Location: `api/grades.php`, `api/login.php`
- Flaw and class: The grades API accepts a token with the `none` algorithm and treats it as valid. The signed path uses a weak shared secret. The role is read from the token without a server side check. The login endpoint issues tokens with a one hour lifetime and no issuer or audience check. This is a token validation failure.
- OWASP 2025: A07 Authentication Failures
- CWE: CWE-347 Improper Verification of Cryptographic Signature, CWE-287 Improper Authentication, CWE-798 Use of Hard-coded Credentials
- Flag location: The administrative grades response.
- Fix idea: Accept only a fixed algorithm allowlist. Reject `none` and unexpected algorithms. Load a strong secret from the environment or a key service. Verify the signature, issuer, audience and expiry. Check authorization on the server for every request. Keep token lifetimes short and support rotation and revocation.

### A07.2 Session and Remember Me

- Module key: `a07-session`
- Location: `login.php`, `account.php`, `logout.php`, `config.php`
- Flaw and class: The remember me cookie is just the base64 encoded username, so it can be forged. The session identifier is accepted from the URL and is not regenerated at login, which allows fixation. Logout clears the user fields but leaves the session record. Cookies lack Secure and HttpOnly. The application accepts default and weak credentials, has no password policy, and returns different errors for a missing user and a wrong password. This is a set of authentication and session management failures.
- OWASP 2025: A07 Authentication Failures
- CWE: CWE-384 Session Fixation, CWE-613 Insufficient Session Expiration, CWE-614 Sensitive Cookie in HTTPS Session Without Secure Attribute, CWE-521 Weak Password Requirements, CWE-204 Observable Response Discrepancy
- Flag location: The account page when the remember me value decodes to the administrator name.
- Fix idea: Regenerate the session identifier at login and privilege change. Accept session identifiers only from cookies. Use a random remember me token stored hashed on the server, with expiry and revocation. Destroy the session and the cookie on logout. Set Secure, HttpOnly and SameSite. Enforce a password policy and check passwords against a breach list. Return a single generic error for failed authentication. Remove default accounts.

## A08:2025 Software or Data Integrity Failures

### A08.1 Preference Cookie

- Module key: `a08-unserialize`
- Location: `prefs.php`, `lib/LabNotice.php`
- Flaw and class: The preference cookie is passed to `unserialize`. A lab class with a destructor reads a file, so a crafted object triggers file disclosure. This is insecure deserialization with a local gadget.
- OWASP 2025: A08 Software or Data Integrity Failures
- CWE: CWE-502 Deserialization of Untrusted Data
- Flag location: The file read by the gadget class.
- Fix idea: Store preferences as JSON and validate the schema. Never pass untrusted data to `unserialize`. If serialization is unavoidable, use a signed format and an allowlist of classes. Keep secrets out of files reachable by the web user.

### A08.2 Update Check

- Module key: `a08-integrity`
- Location: `admin/update.php`, `app_settings`
- Flaw and class: The update page fetches a manifest over plain HTTP and displays it without checking a signature or checksum. The manifest carries the flag. The fee page also trusts client side values, which is a data integrity problem. This is an unsigned update and data trust failure.
- OWASP 2025: A08 Software or Data Integrity Failures
- CWE: CWE-345 Insufficient Verification of Data Authenticity, CWE-494 Download of Code Without Integrity Check
- Flag location: The update manifest response.
- Fix idea: Serve updates over TLS from an authenticated source. Sign the manifest and verify the signature before use. Verify a checksum of the package. Reject unsigned or expired metadata. Keep fee amounts and other critical values on the server. Record the applied update and its signature.

## A09:2025 Security Logging and Alerting Failures

### A09.1 Exposed Application Log

- Module key: `a09-log`
- Location: `logs/app.log`, `admin/logs.php`
- Flaw and class: The application log sits in the web root and records passwords, relay keys and a vault marker. Anyone can read it. No alert is raised for failed logins or administrative actions. This is sensitive information in logs combined with missing detection.
- OWASP 2025: A09 Security Logging and Alerting Failures
- CWE: CWE-532 Insertion of Sensitive Information into Log File, CWE-538 Insertion of Sensitive Information into Externally-Accessible File or Directory
- Flag location: The vault marker in the log file.
- Fix idea: Write logs outside the web root. Redact passwords, tokens and keys before writing. Restrict access to the log system. Set retention and rotation. Alert on failed login bursts, privilege changes and destructive actions. Send security events to a central collector.

### A09.2 Log Viewer

- Module key: `a09-logxss`
- Location: `admin/logs.php`
- Flaw and class: The viewer appends a note from the query string to the log and then prints every line as raw HTML. A note can inject script, which runs in the viewer and can read the readable cookie that carries the flag. This is log injection that becomes stored cross site scripting.
- OWASP 2025: A09 Security Logging and Alerting Failures
- CWE: CWE-117 Improper Output Neutralization for Logs, CWE-79 Improper Neutralization of Input During Web Page Generation
- Flag location: The readable `logxss` cookie.
- Fix idea: Encode every log line for HTML when rendering. Strip or encode control characters and newlines before writing. Use structured logging with separate fields. Mark cookies HttpOnly and SameSite. Add a content security policy. Restrict the viewer to authorized administrators.

## A10:2025 Mishandling of Exceptional Conditions

### A10.1 Authentication Fail Open

- Module key: `a10-failopen`
- Location: `login.php`, `config.php`
- Flaw and class: When the database connection is unavailable or the login query fails, the application creates an administrator session instead of denying access. A query parameter can force the database path to fail. This is a fail open authentication decision.
- OWASP 2025: A10 Mishandling of Exceptional Conditions
- CWE: CWE-636 Not Failing Securely, CWE-287 Improper Authentication
- Flag location: The dashboard after the emergency session is created.
- Fix idea: Fail closed. Return a generic service unavailable message and do not create a session. Separate health checks from authentication. Remove emergency bypass paths from production. Alert on database failures. Test the error path as part of the authentication test suite.

### A10.2 Verbose Errors

- Module key: `a10-errors`
- Location: `config.php`, `admin/bulk.php`
- Flaw and class: The application displays errors, startup warnings and SQL messages in the response. A custom error handler prints the file path, line number and a flag. The bulk import accepts an unbounded row count and builds the whole batch in memory. This is verbose error disclosure combined with unbounded resource use.
- OWASP 2025: A10 Mishandling of Exceptional Conditions
- CWE: CWE-209 Generation of Error Message Containing Sensitive Information, CWE-400 Uncontrolled Resource Consumption
- Flag location: The debug block printed by the error handler.
- Fix idea: Disable display errors in production and show a generic error page. Log the detail on the server with a request identifier. Return a safe message to the client. Bound input sizes, row counts and execution time. Stream large jobs and apply quotas. Test with arrays, nulls and oversized values.

## Background from the original review

The original code review described the same weaknesses in a shorter form. This section keeps that material as prose.

The login and registration handlers built SQL statements from posted values. An attacker could change the meaning of the query, bypass authentication or insert a row without the expected fields. The fix is to bind parameters and to verify a password hash instead of comparing plaintext.

The feedback, contact and news pages rendered user input as HTML. Stored feedback and contact messages could run script in another user's browser, and the news search reflected input back to the sender. The fix is contextual output encoding, a content security policy and careful handling of any rich text.

The forms had no cross site request forgery tokens. A third party page could submit feedback or another state change on behalf of a signed in user. The fix is a per session token that the server verifies before processing the request.

The administration page deleted accounts from a GET request and had no role check. Any visitor could remove a user. The fix is an authenticated administrator check, a POST request and a CSRF token.

The upload page accepted any file and kept the original name. A PHP file could be uploaded and executed. The fix is a type allowlist, content verification, a random name, storage outside the web root and disabled script execution in the upload directory.

The backup folder and environment file were served from the web root. They held database credentials, mail settings and a lab flag. The fix is to keep secrets outside the document root, block dotfiles and backup extensions at the server, and remove directory listing.

The session cookie lacked Secure and HttpOnly, the session identifier was accepted from the URL, and logout did not destroy the session. The fix is to regenerate the identifier at login, accept it only from a cookie, set the protective flags and destroy the session on logout.

These findings match the modules above. The lab keeps them in place on purpose. Use this file to teach the flaw, the impact and the fix, then compare the vulnerable code with a corrected version in a separate branch or exercise.

## Note on scope

Every module runs inside the local lab. Command injection, file upload and the theme installer affect only the web container. The notice import can reach the private internal service. Nothing in the lab is designed to persist outside the containers.

Run the lab on localhost only. Use an isolated host or virtual machine when you can. Never expose it to a network.
