---

description: "Task list for Daily Labour Finder (Laravel MVP)"
---

# Tasks: Daily Labour Finder (Laravel MVP)

**Input**: Design documents from `/specs/001-today-labour-discovery/`
**Prerequisites**: `plan.md` (required), `spec.md` (required), `research.md`, `data-model.md`, `contracts/`, `quickstart.md`

**Tests**: Not included (explicitly excluded by request).

**Constitution Gates (always include tasks/checkpoints)**:
- **Mobile-first**: verify core flow on a small screen.
- **Accessibility**: readable fonts, high contrast, focus states, minimal steps.
- **Scope**: confirm no payments, no ratings, no chat, no GPS tracking.
- **Onboarding**: admin-managed onboarding steps and audit trail.
- **Performance**: define budgets + validate (basic checks).

**Milestones**: Tasks are grouped by the staged rollout from `plan.md`:
- Phase 1: Data model + admin
- Phase 2: Hirer browse UI
- Phase 3: Seed + polish + QA

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (`[US1]`, `[US2]`, `[US3]`)
- **Complexity**: Each task has an `(S/M/L)` tag in the title line
- Each task includes: **Steps** + **Acceptance Criteria** (definition of done)

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Laravel project initialization and baseline tooling

- [x] T001 Initialize Laravel 11 project at repo root (S) — `./`
  - **Steps**:
    - Create Laravel app in repo root (per `quickstart.md`).
    - Create `.env` from `.env.example`; set app key.
    - Configure DB connection (SQLite local OK; MySQL for prod parity).
  - **Acceptance Criteria**:
    - `php artisan --version` works.
    - `php artisan serve` runs without errors.

- [x] T002 Install Breeze (Blade) and build assets baseline (M) — `composer.json`, `resources/`, `routes/`
  - **Steps**:
    - Install Laravel Breeze (Blade) and run required installers.
    - Install npm deps and run build/dev once.
  - **Acceptance Criteria**:
    - Login/register views are generated.
    - Home route renders with Tailwind assets loading.

- [x] T003 [P] Configure public storage for photos (S) — `config/filesystems.php`, `storage/app/public/`
  - **Steps**:
    - Ensure `public` disk is configured.
    - Run `php artisan storage:link` (document in quickstart).
  - **Acceptance Criteria**:
    - Files saved to `storage/app/public` are reachable via `/storage/...` in browser.

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core data model, migrations, auth gates, and admin routing foundation

**⚠️ CRITICAL**: No user story work can begin until this phase is complete

- [x] T004 Create migrations for Areas, Skills, Labourers, pivot, Availability (M) — `database/migrations/*.php`
  - **Steps**:
    - Create tables per `data-model.md` (areas, skills, labourers, labourer_skill, availabilities).
    - Add indexes/uniques (notably `availabilities(labourer_id, date)` unique).
  - **Acceptance Criteria**:
    - `php artisan migrate:fresh` completes.
    - Schema matches `data-model.md` entities and indexes.

- [x] T005 Add `is_admin` flag to users + default admin seeder (M) — `database/migrations/*add_is_admin*`, `database/seeders/DatabaseSeeder.php`
  - **Steps**:
    - Migration adds boolean `is_admin` to `users`.
    - Seed a single admin user with known email/password (MVP dev only; document in `quickstart.md`).
  - **Acceptance Criteria**:
    - Admin can log in with seeded credentials in local environment.

- [x] T006 Implement admin-only middleware/gate (S) — `app/Http/Middleware/AdminOnly.php`, `app/Http/Kernel.php`
  - **Steps**:
    - Create middleware that checks `auth()->user()?->is_admin`.
    - Register middleware and apply to admin route group.
  - **Acceptance Criteria**:
    - Non-auth users are redirected to login for `/admin/*`.
    - Auth non-admin users get 403 (or redirected) for `/admin/*`.

- [x] T007 Create Eloquent models + relationships (M) — `app/Models/{Area,Skill,Labourer,Availability}.php`
  - **Steps**:
    - Add fillable/casts and relationships:
      - `Area hasMany Labourer`
      - `Labourer belongsTo Area`, `belongsToMany Skill`, `hasMany Availability`
      - `Skill belongsToMany Labourer`
      - `Availability belongsTo Labourer`
  - **Acceptance Criteria**:
    - Tinker can create records and traverse relationships without errors.

- [x] T008 Add form request validation for admin inputs (M) — `app/Http/Requests/*`
  - **Steps**:
    - Create `StoreLabourerRequest`/`UpdateLabourerRequest` (name, phone, area, skills, photo).
    - Validate `phone_e164` as a string pattern (MVP: simple E.164-ish rule).
  - **Acceptance Criteria**:
    - Invalid payloads return validation errors and do not persist.

**Checkpoint**: Foundation ready — migrations + admin auth gate + core models exist.

---

## Phase 3: User Story 3 - Admin Onboards Labourers & Hirers (Priority: P3)

**Goal**: Admin can manage labourers (CRUD), areas/skills (minimal), and access control.

**Independent Test**: Admin logs in and can create/edit/deactivate a labourer with skills + area.

### Implementation

- [ ] T009 Build admin route group + navigation shell (S) — `routes/web.php`, `resources/views/admin/layout.blade.php`
  - **Steps**:
    - Add `/admin` prefixed routes behind `auth` + `admin` middleware.
    - Create a simple admin layout with mobile-first nav (Labourers, Availability Today).
  - **Acceptance Criteria**:
    - Admin pages share consistent layout; nav works on mobile width.

- [ ] T010 Implement admin labourer list + search/pagination (M) — `app/Http/Controllers/Admin/LabourerController.php`, `resources/views/admin/labourers/index.blade.php`
  - **Steps**:
    - Index page with search by name/phone, filter by area, paginate.
    - Show active/inactive state.
  - **Acceptance Criteria**:
    - Admin can find a labourer quickly with search/filter; page loads fast with pagination.

- [ ] T011 Implement admin labourer create/edit forms (M) — `resources/views/admin/labourers/{create,edit}.blade.php`
  - **Steps**:
    - Form fields: name, phone, area, skills multi-select, active toggle, photo upload.
    - Mobile-first layout with large tap targets and accessible labels.
  - **Acceptance Criteria**:
    - Admin can create and update a labourer successfully.
    - Validation errors are readable and announced near fields.

- [ ] T012 Implement labourer create/update handlers incl. skills pivot sync (M) — `app/Http/Controllers/Admin/LabourerController.php`
  - **Steps**:
    - Use Form Requests from T008.
    - Save labourer, sync skills pivot.
  - **Acceptance Criteria**:
    - Labourer persists with correct area and skills.

- [ ] T013 Implement labourer photo upload + storage path persistence (M) — `app/Http/Controllers/Admin/LabourerController.php`
  - **Steps**:
    - Accept image upload; store to `public` disk (e.g., `labourers/` folder).
    - Save `photo_path` on labourer.
    - Handle optional photo removal/replace (minimal MVP: replace only).
  - **Acceptance Criteria**:
    - Uploaded photo is visible on admin edit page and public browse cards.

- [ ] T014 Add minimal admin management for Areas & Skills (M) — `app/Http/Controllers/Admin/{AreaController,SkillController}.php`, `resources/views/admin/{areas,skills}/*`
  - **Steps**:
    - Keep MVP minimal: list + create + deactivate (optional edit).
    - Ensure uniqueness constraints are enforced with user-friendly errors.
  - **Acceptance Criteria**:
    - Admin can add areas/skills and deactivate them without breaking existing labourers.

---

## Phase 4: User Story 1 - Find & Contact Available Labourers (Priority: P1) 🎯 MVP

**Goal**: Public hirer page to browse “available today” labourers by area/skills and call them.

**Independent Test**: Select an area with seeded availability for today; see cards; tap “Call” opens dialer.

### Implementation

- [ ] T015 Implement public browse route + controller query (M) — `routes/web.php`, `app/Http/Controllers/BrowseController.php`
  - **Steps**:
    - `GET /` reads `area`, optional `skills[]`, optional `q`.
    - Query labourers: active, in selected area, has availability row for today.
    - Apply skills filter via pivot and name search via `q`.
  - **Acceptance Criteria**:
    - Results match filters and show only “today available” labourers.
    - Empty state when no results.

- [ ] T016 Build mobile-first browse UI (M) — `resources/views/browse/index.blade.php`, `resources/css/app.css`
  - **Steps**:
    - Area selector (required), skills multi-select, search input.
    - Render labourer cards with photo, name, skills, and “Call” CTA.
    - Use high contrast, readable font sizing, and clear focus styles.
  - **Acceptance Criteria**:
    - No horizontal scrolling on mobile widths.
    - “Call” is one tap and uses `tel:` link with accessible label.

- [ ] T017 Add lightweight performance helpers (S) — `app/Http/Controllers/BrowseController.php`
  - **Steps**:
    - Add indexes already in migrations; ensure eager loading for skills to avoid N+1.
    - Cache areas/skills lists for the browse filters (simple cache key).
  - **Acceptance Criteria**:
    - Browse page stays responsive with hundreds of labourers.

---

## Phase 5: Availability Dashboard (Admin-managed “today”) — MVP requirement

**Goal**: Admin can mark labourers available today in bulk; browse uses it.

**Independent Test**: Admin marks a labourer available today; labourer appears on public page for that area.

- [ ] T018 Implement admin availability dashboard UI (M) — `app/Http/Controllers/Admin/AvailabilityTodayController.php`, `resources/views/admin/availability/today.blade.php`
  - **Steps**:
    - Show labourers filtered by area/skill and current availability state for today.
    - Provide bulk toggle controls (checkbox list + “Mark Available” / “Remove”).
  - **Acceptance Criteria**:
    - Admin can change availability for multiple labourers in one action.
    - UI works well on mobile (scrollable list, sticky action bar).

- [ ] T019 Implement availability upsert logic (M) — `app/Http/Controllers/Admin/AvailabilityTodayController.php`, `app/Models/Availability.php`
  - **Steps**:
    - For selected labourers: upsert availability row for today.
    - For removals: delete today’s row (or set status to unavailable).
  - **Acceptance Criteria**:
    - Availability updates reflect immediately in browse query.
    - No duplicates for same labourer/date.

- [ ] T020 Implement daily reset strategy (S) — `app/Console/Kernel.php`, `app/Console/Commands/PruneOldAvailability.php`
  - **Steps**:
    - Choose MVP approach: date-keyed rows mean “today” naturally resets.
    - Add optional scheduled command to prune rows older than N days (e.g., 30).
    - Document cron setup in `quickstart.md` (already noted).
  - **Acceptance Criteria**:
    - New day starts with no availability unless admin marks it.
    - (If scheduler enabled) old rows are cleaned on schedule.

---

## Phase 6: Seed + Polish + QA (Phase 3 in plan)

**Purpose**: Demo-ready dataset, synthetic photos, UI polish, and gate validation

- [ ] T021 Create factories for Area/Skill/Labourer/Availability (M) — `database/factories/*.php`
  - **Steps**:
    - Implement factories with sensible fake values.
    - Ensure labourer phone values are unique-ish and valid-ish for E.164 format.
  - **Acceptance Criteria**:
    - Factories can generate records without violating constraints.

- [ ] T022 Implement seeders for areas + skills (S) — `database/seeders/{AreaSeeder,SkillSeeder}.php`
  - **Steps**:
    - Seed a small curated list + a few extra fakes.
    - Mark all active by default.
  - **Acceptance Criteria**:
    - Areas/skills appear in filters and admin pages.

- [ ] T023 Add synthetic photo pool to repo + seeder copy logic (M) — `database/seeders/assets/labourers/*`, `database/seeders/Concerns/SeedsPhotos.php`
  - **Steps**:
    - Add a small set of synthetic/placeholder images under a seed assets folder.
    - Write helper that copies a random image into `storage/app/public/labourers/` and returns the path.
  - **Acceptance Criteria**:
    - After seeding, labourers have photos that render via `/storage/...`.

- [ ] T024 Implement LabourerSeeder: assign areas, skills, photo, and active flag (M) — `database/seeders/LabourerSeeder.php`
  - **Steps**:
    - Create N labourers; assign random area and 1–3 skills.
    - Assign `photo_path` using the photo pool helper.
  - **Acceptance Criteria**:
    - Seeded labourers show up in admin list with photos and skills.

- [ ] T025 Implement AvailabilitySeeder for “today” (S) — `database/seeders/AvailabilitySeeder.php`
  - **Steps**:
    - Create availability rows for “today” for a subset of labourers per area.
  - **Acceptance Criteria**:
    - Public browse shows at least some labourers for each seeded area (where intended).

- [ ] T026 Wire seeders into DatabaseSeeder + document seeded admin credentials (S) — `database/seeders/DatabaseSeeder.php`, `specs/001-today-labour-discovery/quickstart.md`
  - **Steps**:
    - Call seeders in order: areas/skills → labourers → availability → admin user.
    - Ensure idempotent-ish behavior for local dev (migrate:fresh --seed).
  - **Acceptance Criteria**:
    - One command `php artisan migrate:fresh --seed` yields a usable demo.

- [ ] T027 Mobile-first + accessibility polish pass (S) — `resources/views/**/*.blade.php`, `resources/css/app.css`
  - **Steps**:
    - Ensure readable typography, spacing, and tap targets.
    - Verify keyboard focus states and labels for form controls.
    - Confirm minimal steps on browse: select area → results → call.
  - **Acceptance Criteria**:
    - Meets constitution gates for mobile-first + accessibility.

- [ ] T028 Scope + security hardening pass (S) — `routes/web.php`, `app/Http/Middleware/*`
  - **Steps**:
    - Confirm no payments/ratings/chat/GPS features slipped in.
    - Ensure admin routes are protected; disable register route if not desired for MVP.
  - **Acceptance Criteria**:
    - Admin-only pages are not reachable by unauthenticated users.

---

## Dependencies & Execution Order

- **Setup (T001–T003)** → **Foundation (T004–T008)** → **Admin (T009–T014)** → **Availability (T018–T020)** → **Public Browse (T015–T017)** → **Seed/Polish (T021–T028)**

## Parallel Opportunities

- [P] tasks can run in parallel once Laravel is initialized:
  - T003 (storage link) can be done early.
  - Factories/seed assets (T021–T023) can be prepared while admin pages are built.


