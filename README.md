# Campus-Buzz

A PHP-based campus social and registration platform with email verification, built on MySQLi and PHPMailer.

## Features
- User registration with email verification (a code is emailed via PHPMailer, confirmed in `verify.php`)
- Login/authentication (`aunthenticate.php`)
- Prepared-statement database queries throughout (no raw string-interpolated SQL)

## Tech stack
PHP, MySQL (MySQLi), PHPMailer.

## Setup
1. Copy `Campus Buzz/config.example.php` to `Campus Buzz/config.php`.
2. Fill in your own database credentials and SMTP (Gmail app password) credentials in `config.php` — this file is gitignored and never committed.
3. Import the schema into MySQL (table: `userinformation`).
4. Serve the `Campus Buzz` folder with PHP (e.g. `php -S localhost:8000` from inside it, with a configured MySQL server running).

## Security notes
Credentials are loaded from `config.php` (gitignored) via `define()` constants — never hardcoded in the PHP files. All database queries use prepared statements.

