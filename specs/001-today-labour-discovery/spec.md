# Feature Specification: Today Labour Discovery

**Feature Branch**: `001-today-labour-discovery`
**Created**: 2025-12-17
**Status**: Draft
**Input**: In my area, it’s difficult to get daily labour. Individuals/contractors have to go to
the chowk early morning and find labour manually. Labourers also waste time waiting without
guarantee of work. MVP: Help hirers quickly discover available labourers in a specific area for
“today” and contact them immediately.

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Find & Contact Available Labourers (Priority: P1)

As a hirer (individual/contractor), I want to select an area and see who is available **today**
so I can contact a labourer immediately and arrange work.

**Why this priority**: This is the MVP goal and saves the most time for both sides.

**Independent Test**: Using a test account, select an area with at least one “available today”
labourer and successfully initiate contact from a mobile device in minimal steps.

**Acceptance Scenarios**:

1. **Given** I am an onboarded hirer, **When** I choose an area, **Then** I see a list of labourers
   available “today” in that area.
2. **Given** I am viewing the “today” list, **When** I tap a labourer entry, **Then** I can
   immediately contact them via the supported method(s) in one step.
3. **Given** no labourers are available today in the selected area, **When** I open the list,
   **Then** I see a clear empty state and how to try another area/filter.

---

### User Story 2 - Mark Myself Available Today (Priority: P2)

As a labourer, I want to mark myself available (or unavailable) for **today** so hirers can find
me without me waiting at the chowk with no guarantee.

**Why this priority**: Availability accuracy is essential for discovery to work.

**Independent Test**: Using a labourer account, change today’s availability and confirm the hirer
view updates accordingly.

**Acceptance Scenarios**:

1. **Given** I am an onboarded labourer, **When** I set my status to “Available today”, **Then**
   hirers searching my area can see me in their “today” list.
2. **Given** I am “Available today”, **When** I set my status to “Not available”, **Then** I no
   longer appears in the “today” list.

---

### User Story 3 - Admin Onboards Labourers & Hirers (Priority: P3)

As an admin, I want to add/update labourer and hirer profiles and assign them to areas so the
system stays trustworthy and usable.

**Why this priority**: Admin-managed onboarding is a constitutional requirement; it also reduces
spam and low-quality listings.

**Independent Test**: Admin creates a labourer profile assigned to an area; that labourer can then
appear in the hirer “today” list when marked available.

**Acceptance Scenarios**:

1. **Given** I am an admin, **When** I create a labourer profile with name/skills/phone and area,
   **Then** that labourer can be discovered by hirers for that area (when available today).
2. **Given** I am an admin, **When** I deactivate a labourer profile, **Then** they do not appear
   in discovery even if previously available.

---

### Edge Cases

- No “available today” labourers exist for an area (clear empty state).
- A labourer has missing/invalid contact details (labourer entry cannot be contacted; shown as
  incomplete to admin only).
- A labourer toggles availability rapidly (system shows the latest state; no confusing duplicates).
- A hirer tries to access without being onboarded (blocked + guidance to contact admin).
- Accessibility: large text sizes or screen readers (content still readable and navigable).

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST support admin-managed onboarding for hirers and labourers.
- **FR-002**: System MUST allow admins to create, update, deactivate, and assign users to areas.
- **FR-003**: System MUST let a hirer select an area and view labourers available **today** in that
  area.
- **FR-004**: System MUST support a labourer “available today” status that affects hirer discovery.
- **FR-005**: System MUST allow hirers to contact a labourer immediately via **phone call**.
- **FR-006**: System MUST allow **labourers** to update their own “available today” status.
- **FR-007**: System MUST provide an area model suitable for selection without GPS tracking: an
  **admin-maintained list of chowk/locality names**.
- **FR-008**: System MUST display labourer details needed for hiring decisions (e.g., name, work
  type/skills, and contact path) in a mobile-first, accessible layout.
- **FR-009**: System MUST show clear states for loading, empty results, and access blocked (not
  onboarded).

### Key Entities *(include if feature involves data)*

- **Area**: A named place used for search/filtering (no GPS tracking); maintained by admins.
- **Labourer Profile**: Identity + basic details (name, skills/work type, contact info), assigned area,
  active/inactive, availability for today.
- **Hirer Profile**: Identity + basic details, assigned area(s) or allowed areas, active/inactive.
- **Availability (Today)**: A state for a given date (“today”) indicating the labourer is available.

### Non-Goals & Constraints *(mandatory)*

- **NG-001**: Feature MUST NOT include payments (billing, subscriptions, payouts, wallets).
- **NG-002**: Feature MUST NOT include ratings/reviews.
- **NG-003**: Feature MUST NOT include chat/messaging.
- **NG-004**: Feature MUST NOT include GPS tracking / location tracking.
- **C-001**: UI MUST be mobile-first and accessible (readable fonts, high contrast, minimal steps).
- **C-002**: Onboarding MUST be admin-managed (no self-serve by default).
- **C-003**: Feature MUST define performance budgets and how they’ll be validated.

### Assumptions

- The “area” list is finite and admin-maintained (e.g., chowk/locality names).
- Hirers need “contact now” more than rich profiles; profile fields will stay minimal in MVP.
- “Today” is based on the local date configured by the product (no timezone selection in MVP).

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: A hirer can go from opening the app to initiating contact with an available labourer
  in **under 60 seconds** on a typical mobile device.
- **SC-002**: **95%** of hirer searches show results (or an explicit empty state) in **under 2
  seconds**.
- **SC-003**: **80%** of onboarded labourers who log in can mark availability for today in **under
  15 seconds** (minimal steps).
- **SC-004**: In pilot use, hirers report **reduced time at the chowk** (self-reported reduction of
  at least **30 minutes** on days they hire via the app).
