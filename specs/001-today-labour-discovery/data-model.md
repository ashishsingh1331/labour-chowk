# Data Model: Daily Labour Finder (MVP)

**Date**: 2025-12-17
**Branch**: `001-today-labour-discovery`

## Entities

### Area
- **Purpose**: Admin-maintained chowk/locality list for filtering (no GPS).
- **Fields**:
  - `id`
  - `name` (unique)
  - `is_active` (boolean)
  - timestamps

### Skill
- **Purpose**: Normalized list of work types (e.g., masonry, painter).
- **Fields**:
  - `id`
  - `name` (unique)
  - `is_active` (boolean)
  - timestamps

### Labourer
- **Purpose**: Labourer profile used in discovery.
- **Fields**:
  - `id`
  - `full_name`
  - `phone_e164` (stored as text; validate format on save)
  - `area_id` (FK to Area)
  - `photo_path` (nullable; path on public disk)
  - `is_active` (boolean)
  - timestamps

### LabourerSkill (pivot)
- **Purpose**: Labourer ↔ Skill many-to-many.
- **Fields**:
  - `labourer_id` (FK)
  - `skill_id` (FK)
  - unique index (`labourer_id`, `skill_id`)

### Availability
- **Purpose**: “Available today” state keyed by date (MVP: admin-managed).
- **Fields**:
  - `id`
  - `labourer_id` (FK to Labourer)
  - `date` (YYYY-MM-DD)
  - `status` (enum/string: `available` | `unavailable`) — MVP can store only `available` rows
  - timestamps
- **Indexes**:
  - unique index (`labourer_id`, `date`)
  - index (`date`)
  - index (`date`, `labourer_id`)

### Admin User
- **Purpose**: Admin-managed onboarding + admin dashboard access.
- **Approach**: Use Laravel `users` table with an `is_admin` boolean.

## Migrations (planned)

- `create_areas_table`
- `create_skills_table`
- `create_labourers_table`
- `create_labourer_skill_table`
- `create_availabilities_table`
- `add_is_admin_to_users_table`

## Validation rules (MVP)

- Area/Skill names: trimmed, unique, 2–80 chars.
- Labourer:
  - `full_name`: required, 2–80 chars
  - `phone_e164`: required, valid E.164-like string (store as text)
  - `area_id`: required, must reference active area
  - `photo_path`: optional
  - `is_active`: default true

## State & business rules

- Discovery shows only:
  - labourers with `is_active = true`
  - whose area matches the selected area
  - who have **availability status for today** (or an “available row exists” for today)
- Admin can toggle availability for today per labourer (bulk controls in dashboard).

