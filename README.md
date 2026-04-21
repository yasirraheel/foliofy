# Foliofy Laravel Migration

This repository now runs as a full Laravel application created through Composer and then adapted to preserve the original Foliofy portfolio behavior and UI.

## What Stayed The Same

- Public portfolio UI and markup
- Admin dashboard UI and interactions
- Frontend JavaScript behavior
- Legacy browser-facing URLs:
  - `/`
  - `/index.html`
  - `/admin`
  - `/admin.html`
- Legacy endpoint URLs used by the existing JavaScript:
  - `/api_messages.php`
  - `/upload.php`
  - `/wa_proxy.php`

## What Changed

- The project is now a real Laravel application with `artisan`, `app/`, `bootstrap/`, `config/`, `database/`, `public/`, `resources/`, `routes/`, `storage/`, `tests/`, and `vendor/`.
- The old standalone PHP endpoint behavior now runs through Laravel controllers.
- The original HTML was copied into Laravel-served files so the UI remains the same.
- Static assets were moved into `public/` for Laravel delivery.

## Project Layout

- `routes/web.php`
  - Laravel routes for the public page, admin page, and legacy endpoint URLs
- `app/Http/Controllers/Legacy`
  - Laravel replacements for `api_messages.php`, `upload.php`, and `wa_proxy.php`
- `resources/views/portfolio.blade.php`
  - Original portfolio HTML served by Laravel
- `resources/views/admin.blade.php`
  - Original admin HTML served by Laravel
- `public/`
  - CSS, JS, images, and other browser assets

## Requirements

- PHP 8.3+
- Composer 2+

PHP and Composer were installed globally for this machine during the migration. If a currently open terminal still does not recognize `php` or `composer`, open a fresh terminal window so it picks up the updated PATH.

## Run Locally

```bash
composer install
php artisan serve
```

Then open:

- `http://127.0.0.1:8000/`
- `http://127.0.0.1:8000/admin.html`

## Live Server Note

For Apache/shared hosting, the preferred document root is still `public/`.

This repository now also includes a root `index.php` and root `.htaccess` bridge so the app still routes into Laravel if the live server is pointed at the project root by mistake. That bridge forwards:

- `index.html` and `admin.html` into Laravel routes
- browser assets like `data.js`, `renderer.js`, `script.js`, `admin.js`, `style.css`, and images into `public/`
- legacy endpoints like `api_messages.php`, `upload.php`, and `wa_proxy.php` into Laravel

## Testing

```bash
php artisan test
```

## Notes

- Portfolio content is MySQL-backed through Laravel. The public page reads Laravel-injected data, and the admin dashboard saves portfolio changes back into the database.
- Uploaded images are stored under `public/uploads/`.
- Contact messages are stored in MySQL.
- The old root-level static files remain in the repository only as legacy artifacts, but the root bridge now routes browser requests into Laravel/public so those stale copies do not drive the live site.
