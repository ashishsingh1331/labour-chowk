# Labour Chowk Constitution
<!--
Sync Impact Report
- Version change: template → 0.1.0 (first concrete constitution; prior file was an unfilled template)
- Modified principles: N/A (template placeholders → defined principles)
- Added sections: Product Scope & Non-Goals; Delivery & Quality Gates
- Removed sections: N/A
- Templates requiring updates:
  - ✅ `.specify/templates/plan-template.md`
  - ✅ `.specify/templates/spec-template.md`
  - ✅ `.specify/templates/tasks-template.md`
  - ✅ `.specify/templates/checklist-template.md`
- Deferred items:
  - TODO(RATIFICATION_DATE): original adoption date unknown; set this once confirmed
-->

## Core Principles

### 1) Mobile-First by Default
- The primary UX MUST work end-to-end on small screens without horizontal scrolling.
- All critical flows MUST be usable with one hand and minimal typing where possible.
- Desktop layouts MAY exist, but MUST NOT be required to complete core tasks.

**Rationale**: Most users will use the product on mobile; optimize for that reality.

### 2) Accessibility is a Release Gate
- Text MUST be readable: use legible fonts, adequate line-height, and avoid dense blocks.
- UI MUST use high contrast and clear focus states.
- Flows MUST minimize steps and cognitive load (prefer defaults, progressive disclosure).
- Interactive elements MUST be reachable via keyboard and have accessible names/labels.

**Rationale**: If users can’t read or operate the UI, the feature does not exist.

### 3) Tight Scope (Non-Goals are Binding)
- The product MUST NOT implement: payments, ratings, chat, or GPS tracking.
- If a feature request implies any non-goal, the default response is “no” unless the
  constitution is amended first.

**Rationale**: Scope discipline keeps delivery fast and reduces privacy/legal risk.

### 4) Admin-Managed Onboarding
- User onboarding MUST be controlled by admins (creation/invite/approval) rather than
  self-serve registration by default.
- Any self-serve entry points (if added later) MUST be explicitly approved via a
  constitution amendment.

**Rationale**: Operational control and trust are essential for early-stage rollout.

### 5) Performance is a Feature
- Core screens MUST feel fast; avoid heavy dependencies and unnecessary round-trips.
- New UI work MUST meet basic performance budgets defined in each feature plan
  (e.g., interaction latency, page load time, bundle size).

**Rationale**: Slow mobile UX is unusable UX.

## Product Scope & Non-Goals

**In scope**: A simple, admin-operated system with a mobile-first, accessible UI.

**Hard non-goals (do not build)**:
- Payments (subscriptions, wallets, payouts, billing)
- Ratings/reviews
- Chat/messaging
- GPS tracking / location tracking

**Privacy stance**:
- Collect the minimum data needed for the user journey; avoid sensitive data unless
  explicitly required and justified in the spec.

## Delivery & Quality Gates

- **Accessibility gate**: Each feature spec MUST include acceptance criteria covering:
  readable typography, contrast, focus order, and minimal-step completion.
- **Performance gate**: Each feature plan MUST define measurable budgets and a quick
  way to validate them (local profiling, Lighthouse, simple timing checks, etc.).
- **Scope gate**: Each feature spec MUST restate non-goals and confirm no hidden
  requirements introduce payments/ratings/chat/GPS.
- **Admin onboarding gate**: Features that create users or grant access MUST specify
  the admin workflow and the audit trail required (who did what, when).

## Governance

- This constitution is the source of truth for product scope and delivery gates.
- **Amendment process**:
  - Propose changes in writing (what changes, why, and the impact on scope/risk).
  - Bump version using semantic versioning:
    - MAJOR: changes to non-goals, onboarding control, or removal/weakening of gates
    - MINOR: new principle/section or material expansion of existing guidance
    - PATCH: clarifications/typos with no semantic behavior change
  - Record `Last Amended` as the date of the approved change.
- **Compliance expectation**:
  - Specs/plans/tasks MUST include a “Constitution Check” that confirms compliance
    or documents approved violations with rationale.

**Version**: 0.1.0 | **Ratified**: TODO(RATIFICATION_DATE): original adoption date unknown | **Last Amended**: 2025-12-17
