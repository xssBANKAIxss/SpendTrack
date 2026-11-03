# SpendTrack

SpendTrack is a personal expense tracker built with PHP and the CodeIgniter 4 framework.

## Features

- Register and log in, including Google sign-in
- Email verification codes (OTP) and a PIN for extra security
- Add, edit and delete expenses
- Dashboard with charts and expense reports
- Admin area for managing users (restricted to the main admin)

## Requirements

- PHP 8.1 or newer
- MySQL / MariaDB (for example through XAMPP)
- Composer

## Setup

1. Clone the repository and open the project folder.
2. Install dependencies:
```
   composer install
```
3. Copy the example environment file:
```
   copy env .env
```
4. Open `.env` and fill in your own values: the database, email (SMTP), Google sign-in and `app.superAdminEmail`. **Never commit `.env`.**
5. Generate an encryption key:
```
   php spark key:generate
```
6. Create the database, then run the migrations:
```
   php spark migrate
```
7. Start the app:
```
   php spark serve
```
   Then open http://localhost:8080

## Running the tests

```
composer test
```

## Notes

- Only the email set in `app.superAdminEmail` can open the admin pages.
- Sensitive files (`.env`, logs, sessions) are excluded by `.gitignore`.