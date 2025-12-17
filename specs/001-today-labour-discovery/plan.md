# Implementation Plan: Daily Labour Finder (MVP)

**Branch**: `001-today-labour-discovery` | **Date**: 2025-12-17 | **Spec**: `./spec.md`  
**Input**: Feature specification from `/specs/001-today-labour-discovery/spec.md` + planning constraints from user

**Note**: This template is filled in by the `/speckit.plan` command.

## Summary

Build a small, mobile-first Laravel web app that lets hirers browse labourers available **today**
in a selected **area**, filter by **skills/work type**, and contact labourers via a one-tap
**phone call** CTA. Admins onboard hirers/labourers, manage labourer profiles, and (for MVP)
manage **today’s availability** via an admin dashboard. Seeders provide realistic fake data and a
synthetic photo pool for fast iteration.

## Technical Context

**Language/Version**: PHP 8.3 + Laravel 11.x  
**Primary Dependencies**: Laravel Breeze (Blade), Tailwind CSS, Vite  
**Storage**: MySQL (prod), SQLite (local/dev ok) + Laravel filesystem (public disk) for photos  
**Testing**: PHPUnit + Laravel feature tests  
**Target Platform**: Server-rendered web app (mobile-first responsive UI)  
**Project Type**: Web application (single Laravel app at repo root)  
**Performance Goals**:
- Hirer browse list loads in <2s for p95 on typical mobile connections (cached where practical)
- Admin pages stay responsive with pagination/search  
**Constraints**:
- MVP scope: no payments, no ratings, no chat, no GPS tracking
- Minimal steps UX; high contrast/readable typography
- Use seeders + synthetic photo pool for demo data
- Admin-managed availability for MVP (note: differs from spec’s labourer self-toggle)  
**Scale/Scope**: Small MVP; hundreds–low-thousands of labourers; a few areas initially

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

- **Mobile-first**: Core flow works on a small screen without horizontal scrolling.
- **Accessibility**: Readable typography, high contrast, clear focus states, minimal steps.
- **Scope**: No payments, no ratings, no chat, no GPS tracking (explicitly confirmed).
- **Onboarding**: Admin-managed onboarding is specified (no self-serve by default).
- **Performance**: Budgets defined + validation method listed (e.g., Lighthouse, timings).

## Project Structure

### Documentation (this feature)

```text
specs/001-today-labour-discovery/
├── plan.md              # This file (/speckit.plan command output)
├── research.md          # Phase 0 output (/speckit.plan command)
├── data-model.md        # Phase 1 output (/speckit.plan command)
├── quickstart.md        # Phase 1 output (/speckit.plan command)
├── contracts/           # Phase 1 output (/speckit.plan command)
└── tasks.md             # Phase 2 output (/speckit.tasks command - NOT created by /speckit.plan)
```

### Source Code (repository root)

```text
app/
  Models/
  Http/
    Controllers/
    Middleware/
database/
  factories/
  migrations/
  seeders/
public/
resources/
  views/
  css/
routes/
  web.php
storage/
  app/public/   # labourer photos (public disk)
tests/
  Feature/
```

**Structure Decision**: Single Laravel app at repository root. Use Blade + Tailwind for a fast,
mobile-first UI without a separate SPA frontend.

## Complexity Tracking

> **Fill ONLY if Constitution Check has violations that must be justified**

| Violation | Why Needed | Simpler Alternative Rejected Because |
|-----------|------------|-------------------------------------|
| N/A | N/A | N/A |

## Phase Plan (staged rollout)

This rollout follows your requested stages:

### Phase 1: Data model + admin (MVP core)

- **Create Laravel app** at repo root (Laravel 11.x), configure DB and env.
- **Auth**: Install Breeze (Blade) and enforce `is_admin` middleware for `/admin/*`.
- **Database schema + migrations** (see `data-model.md`):
  - Areas, Skills, Labourers, labourer_skill pivot, Availabilities (date-keyed), add `is_admin` to users.
- **Admin pages** (Blade + Tailwind):
  - Admin login
  - Labourer CRUD (including photo upload + skills assignment)
  - Availability dashboard for **today** (admin-managed)
- **Daily reset approach**:
  - Use date-keyed availability rows; “today” is derived from server date.
  - Optional scheduler task to prune old availability rows.
- **Basic security**:
  - Admin-only routes behind auth + admin gate.
  - Use pagination + input validation.
  - If a call-log endpoint is added later, apply rate limiting.
- **Basic tests**:
  - Feature tests for admin auth (cannot access admin without login / without admin).
  - Feature tests for labourer CRUD validation.
  - Feature test for availability dashboard updates.

### Phase 2: Hirer browse UI (public)

- **Public browse page** (`GET /`):
  - Area selector (required), skills multi-select, name search.
  - List only labourers available **today** for selected area.
  - One-tap **Call** CTA (`tel:+91...`) with accessible labels.
- **UX + accessibility**:
  - High contrast, readable typography, clear focus states, minimal steps.
  - Empty/loading states; no horizontal scrolling on small screens.
- **Performance**:
  - Query optimization + indexes; cache areas/skills list where appropriate.
  - Validate with a lightweight check (e.g., local profiling + manual mobile test).
- **Tests**:
  - Feature tests for browse filters and “available today” logic.

### Phase 3: Seed + polish + QA

- **Seeding strategy** (required):
  - Seed Areas, Skills, Labourers, labourer_skill, and Availability for today.
  - Use factories + a **synthetic photo pool** copied into public storage and assigned to labourers.
- **Polish**:
  - Consistent mobile spacing, large tap targets, error messages.
  - Admin usability (bulk toggles for availability, search).
- **QA checklist**:
  - Constitution gates: scope, accessibility, performance budgets, onboarding.
- **Deployment notes**:
  - `storage:link`, file permissions, and cron (if scheduler used).

## Notes / Spec deltas

- The current spec says labourers can self-toggle availability (`FR-006`), but your planning
  constraint requires **admin-managed availability for MVP**. This plan implements admin-managed
  availability and treats labourer self-toggle as a post-MVP enhancement.
