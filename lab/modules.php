<?php
return [
    [
        'id' => 'A01',
        'name' => 'Broken Access Control',
        'summary' => 'The application lets a user act outside the permissions the design intended.',
        'modules' => [
            [
                'key' => 'a01-idor',
                'name' => 'Profile Record Access',
                'difficulty' => 'Easy',
                'objective' => 'Read another student record by changing an object identifier.',
                'hints' => [
                    'Profile pages often accept an identifier in the query string.',
                    'Try identifiers near your own account.',
                    'The private note field holds the flag for this module.',
                ],
            ],
            [
                'key' => 'a01-admin',
                'name' => 'Administrative Actions',
                'difficulty' => 'Medium',
                'objective' => 'Reach administrative functions without the expected role and change another account.',
                'hints' => [
                    'The admin page is linked in the main navigation.',
                    'Account deletion uses a GET parameter and no role check.',
                    'The administrative notes panel is not protected.',
                ],
            ],
            [
                'key' => 'a01-ssrf',
                'name' => 'Notice Import',
                'difficulty' => 'Hard',
                'objective' => 'Make the server fetch an internal metadata endpoint.',
                'hints' => [
                    'The notice import form accepts a URL.',
                    'The web container can reach the private internal service.',
                    'Try the metadata path on the internal host.',
                ],
            ],
        ],
    ],
    [
        'id' => 'A02',
        'name' => 'Security Misconfiguration',
        'summary' => 'Unsafe defaults and exposed operational material weaken the whole deployment.',
        'modules' => [
            [
                'key' => 'a02-exposure',
                'name' => 'Exposed Files',
                'difficulty' => 'Easy',
                'objective' => 'Find configuration and backup material served from the web root.',
                'hints' => [
                    'Look for dotfiles and backup folders.',
                    'Directory listing is enabled in two places.',
                    'The environment file carries a lab flag.',
                ],
            ],
            [
                'key' => 'a02-xxe',
                'name' => 'XML Course Import',
                'difficulty' => 'Medium',
                'objective' => 'Read a local file through an XML external entity.',
                'hints' => [
                    'The course import accepts XML.',
                    'Declare an entity in the document type definition.',
                    'Use the entity in a field that is echoed back.',
                ],
            ],
            [
                'key' => 'a02-cors',
                'name' => 'API CORS and Headers',
                'difficulty' => 'Medium',
                'objective' => 'Observe the permissive cross origin policy and the missing security headers.',
                'hints' => [
                    'Send an Origin header to the JSON API.',
                    'Credentials are allowed together with a wildcard origin.',
                    'A response header carries the flag.',
                ],
            ],
        ],
    ],
    [
        'id' => 'A03',
        'name' => 'Software Supply Chain Failures',
        'summary' => 'Code and packages enter the system without provenance or integrity checks.',
        'modules' => [
            [
                'key' => 'a03-installer',
                'name' => 'Theme Installer',
                'difficulty' => 'Medium',
                'objective' => 'Install a remote theme package without any integrity check.',
                'hints' => [
                    'The installer accepts a package URL.',
                    'The internal service serves a theme package.',
                    'The package contains a flag file.',
                ],
            ],
            [
                'key' => 'a03-supply',
                'name' => 'Third Party Widget',
                'difficulty' => 'Easy',
                'objective' => 'Inspect a script loaded from a CDN without an integrity attribute.',
                'hints' => [
                    'Check the script tags on the news page.',
                    'The widget is served by the internal service.',
                    'The script exposes a global value.',
                ],
            ],
        ],
    ],
    [
        'id' => 'A04',
        'name' => 'Cryptographic Failures',
        'summary' => 'Sensitive values are stored or transported with weak or unsuitable methods.',
        'modules' => [
            [
                'key' => 'a04-reset',
                'name' => 'Password Reset Tokens',
                'difficulty' => 'Medium',
                'objective' => 'Predict a reset token and complete a reset for another account.',
                'hints' => [
                    'The token is derived from public account data.',
                    'Try a common hash of the username.',
                    'The reset page reveals the flag on success.',
                ],
            ],
            [
                'key' => 'a04-idcard',
                'name' => 'Student ID Card Token',
                'difficulty' => 'Easy',
                'objective' => 'Decode a token that is presented as encrypted.',
                'hints' => [
                    'The card page shows a long token.',
                    'The token uses a common encoding.',
                    'Decode it and read the last field.',
                ],
            ],
        ],
    ],
    [
        'id' => 'A05',
        'name' => 'Injection',
        'summary' => 'Untrusted input changes the meaning of a query, a command or a page.',
        'modules' => [
            [
                'key' => 'a05-sqli',
                'name' => 'Query Injection',
                'difficulty' => 'Medium',
                'objective' => 'Use a UNION query to read a row from another table.',
                'hints' => [
                    'The news search concatenates input into SQL.',
                    'Count the selected columns first.',
                    'The secrets table has a vault value.',
                ],
            ],
            [
                'key' => 'a05-xss',
                'name' => 'Cross Site Scripting',
                'difficulty' => 'Medium',
                'objective' => 'Execute script in the page and read the lab cookie.',
                'hints' => [
                    'Search reflects input without encoding.',
                    'The feedback list stores input.',
                    'The page sets a cookie that script can read.',
                ],
            ],
            [
                'key' => 'a05-rce',
                'name' => 'Command Injection',
                'difficulty' => 'Hard',
                'objective' => 'Run a shell command through the diagnostics tool.',
                'hints' => [
                    'The tool passes a host value to a shell.',
                    'Try a command separator.',
                    'Read the flag file in the container.',
                ],
            ],
            [
                'key' => 'a05-lfi',
                'name' => 'Local File Inclusion',
                'difficulty' => 'Medium',
                'objective' => 'Load a file outside the intended pages directory.',
                'hints' => [
                    'The page loader takes a path.',
                    'Traversal sequences are accepted.',
                    'The flag file is outside the web root.',
                ],
            ],
        ],
    ],
    [
        'id' => 'A06',
        'name' => 'Insecure Design',
        'summary' => 'The business rules themselves permit abuse even when the code runs as written.',
        'modules' => [
            [
                'key' => 'a06-logic',
                'name' => 'Fee Logic',
                'difficulty' => 'Medium',
                'objective' => 'Reuse a coupon and create a payment state the business rules should reject.',
                'hints' => [
                    'The fee page trusts the posted amount.',
                    'Coupon use is not tracked per account.',
                    'A second use produces a receipt with the flag.',
                ],
            ],
            [
                'key' => 'a06-upload',
                'name' => 'File Upload',
                'difficulty' => 'Medium',
                'objective' => 'Upload a server side file and use it to read the upload flag.',
                'hints' => [
                    'The upload form checks nothing.',
                    'The uploads directory executes PHP.',
                    'Read the flag file from the container.',
                ],
            ],
        ],
    ],
    [
        'id' => 'A07',
        'name' => 'Authentication Failures',
        'summary' => 'Identity checks can be bypassed, reused or predicted.',
        'modules' => [
            [
                'key' => 'a07-jwt',
                'name' => 'API Token Forgery',
                'difficulty' => 'Hard',
                'objective' => 'Forge a token that the grades API accepts as an administrator.',
                'hints' => [
                    'The API reads a bearer token.',
                    'The header algorithm is trusted.',
                    'Use an unsigned token with an admin role.',
                ],
            ],
            [
                'key' => 'a07-session',
                'name' => 'Session and Remember Me',
                'difficulty' => 'Medium',
                'objective' => 'Become the administrator through the remember me cookie.',
                'hints' => [
                    'The cookie is not random.',
                    'Decode the cookie value.',
                    'Set it to the administrator username.',
                ],
            ],
        ],
    ],
    [
        'id' => 'A08',
        'name' => 'Software or Data Integrity Failures',
        'summary' => 'The system trusts data or code without checking where it came from or whether it changed.',
        'modules' => [
            [
                'key' => 'a08-unserialize',
                'name' => 'Preference Cookie',
                'difficulty' => 'Hard',
                'objective' => 'Trigger object creation through a serialized cookie.',
                'hints' => [
                    'Preferences are stored in a cookie.',
                    'The value is passed to unserialize.',
                    'A class in the lab reads a file on destruct.',
                ],
            ],
            [
                'key' => 'a08-integrity',
                'name' => 'Update Check',
                'difficulty' => 'Medium',
                'objective' => 'Accept an unsigned update manifest from an internal service.',
                'hints' => [
                    'The admin update page fetches a manifest.',
                    'No signature is checked.',
                    'The manifest carries the flag.',
                ],
            ],
        ],
    ],
    [
        'id' => 'A09',
        'name' => 'Security Logging and Alerting Failures',
        'summary' => 'Important events are not recorded safely, or the record is exposed and untrusted.',
        'modules' => [
            [
                'key' => 'a09-log',
                'name' => 'Exposed Application Log',
                'difficulty' => 'Easy',
                'objective' => 'Read a log file served from the web root and find credentials.',
                'hints' => [
                    'Logs are often left in public folders.',
                    'Try the logs directory.',
                    'The vault marker is in the file.',
                ],
            ],
            [
                'key' => 'a09-logxss',
                'name' => 'Log Viewer',
                'difficulty' => 'Medium',
                'objective' => 'Inject a log line and execute script in the viewer.',
                'hints' => [
                    'The viewer prints lines without encoding.',
                    'User input reaches the log.',
                    'The viewer sets a cookie that script can read.',
                ],
            ],
        ],
    ],
    [
        'id' => 'A10',
        'name' => 'Mishandling of Exceptional Conditions',
        'summary' => 'Errors and edge cases change the security posture instead of being contained.',
        'modules' => [
            [
                'key' => 'a10-failopen',
                'name' => 'Authentication Fail Open',
                'difficulty' => 'Medium',
                'objective' => 'Log in while the database is unavailable.',
                'hints' => [
                    'The login page accepts a database switch.',
                    'The error path grants a session.',
                    'The dashboard shows the flag.',
                ],
            ],
            [
                'key' => 'a10-errors',
                'name' => 'Verbose Errors',
                'difficulty' => 'Easy',
                'objective' => 'Trigger a handled error and read the diagnostic output.',
                'hints' => [
                    'Some parameters expect a scalar value.',
                    'Pass an array instead.',
                    'The debug block includes a flag.',
                ],
            ],
        ],
    ],
];
