# Quickstart: Daily Labour Finder (Laravel MVP)

**Date**: 2025-12-17
**Branch**: `001-today-labour-discovery`

## Local setup

1. Create the Laravel app (repo root):
   - `composer create-project laravel/laravel .`
2. Configure `.env`:
   - DB: use SQLite for quick start or MySQL for parity with prod
3. Install auth scaffolding:
   - Laravel Breeze (Blade) for admin login
4. Build assets:
   - `npm install`
   - `npm run build` (or `npm run dev`)
5. Run migrations + seed:
   - `php artisan migrate --seed`
6. Storage link (photos):
   - `php artisan storage:link`

## Demo data (seeders)

- Seeders create:
  - Areas (chowk/locality list)
  - Skills/work types
  - Labourers with assigned areas + skills
  - Availability rows for “today”
  - Synthetic photos copied from a seed photo pool into the public disk

## Deployment notes (minimal MVP)

### Storage
- Run `php artisan storage:link` on the server.
- Ensure `storage/` and `bootstrap/cache/` are writable.

### Scheduler / cron (daily logic)
Two valid MVP options:
- **No cron required**: availability is keyed by date; a new day starts empty automatically.
- **Optional cron**: run Laravel scheduler to clean old availability rows nightly.

If using cron:
- Add server cron:
  - `* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1`

### Security
- Admin routes require login + admin gate.
- If a call logging endpoint is added later, apply rate limiting and basic abuse controls.

