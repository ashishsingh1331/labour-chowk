# Research & Decisions: Daily Labour Finder (MVP)

**Date**: 2025-12-17
**Branch**: `001-today-labour-discovery`
**Goal**: Resolve implementation choices required for the Laravel MVP plan.

## Decision: Laravel version + baseline stack

- **Decision**: Laravel **11.x** on PHP **8.3**, server-rendered Blade UI, Tailwind CSS, Vite.
- **Rationale**: Fastest path to a clean mobile-first UI with minimal infra and high performance.
- **Alternatives considered**:
  - Laravel + Inertia/React/Vue: more build complexity for an MVP.
  - Full SPA + API: more moving parts; slower to ship.

## Decision: Authentication approach (admin-only for MVP)

- **Decision**: Use **Laravel Breeze (Blade)** for authentication, restrict admin routes behind
  `auth` + an `is_admin` gate/middleware.
- **Rationale**: Minimal setup with standard login pages and sessions; good security defaults.
- **Alternatives considered**:
  - Filament admin panel: faster admin CRUD but adds a larger dependency surface.
  - Laravel Jetstream: more features than needed for MVP.

## Decision: Photo storage strategy

- **Decision**: Store labourer photos on the **public disk** and reference file paths in DB.
  Use `php artisan storage:link` for serving.
- **Rationale**: MVP-friendly, no external services required. Easy for seeders with a synthetic pool.
- **Alternatives considered**:
  - S3: better for scale but unnecessary for MVP.

## Decision: “Today” availability model + daily reset

- **Decision**: Model availability as a row keyed by `labourer_id` + `date`.
  Provide an admin dashboard for the current date. For “reset”, either:
  - **App logic default**: availability is only for the current date; new day starts empty; or
  - **Scheduler**: nightly cleanup of old availability rows (optional).
- **Rationale**: Avoids tricky reset semantics. “Today” changes naturally with date.
- **Alternatives considered**:
  - Boolean `is_available_today` on labourers: requires daily reset and timezone handling.

## Decision: Rate limiting / security for contact actions

- **Decision**: Treat “Call” as a `tel:` link (no server endpoint needed). If we log calls later,
  add a rate-limited POST endpoint.
- **Rationale**: Minimal steps, minimal surface area.
- **Alternatives considered**:
  - Always log calls: adds DB writes and abuse considerations for MVP.

