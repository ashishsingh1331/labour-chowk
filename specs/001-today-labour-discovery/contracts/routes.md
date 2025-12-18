# Route Contracts (Web): Daily Labour Finder (MVP)

**Date**: 2025-12-17
**Branch**: `001-today-labour-discovery`

## Public (hirer) browsing

- `GET /`
  **Purpose**: Browse labourers available today.
  **Query params**:
  - `area` (required): area id
  - `skills[]` (optional): skill ids
  - `q` (optional): name search
  **Response**: Mobile-first list with “Call” CTA (`tel:` link).

## Admin auth

- `GET /admin/login`
- `POST /admin/login`
- `POST /admin/logout`

## Admin: labourers CRUD

- `GET /admin/labourers`
- `GET /admin/labourers/create`
- `POST /admin/labourers`
- `GET /admin/labourers/{labourer}/edit`
- `PUT/PATCH /admin/labourers/{labourer}`
- `DELETE /admin/labourers/{labourer}`

## Admin: availability dashboard (today)

- `GET /admin/availability/today`
- `POST /admin/availability/today`
  **Purpose**: Bulk update today’s availability for selected labourers.

## Optional (future): call logging endpoint

If call logging is added later:
- `POST /call-logs` (rate-limited)
  **Purpose**: Record “call initiated” events without storing sensitive content.

