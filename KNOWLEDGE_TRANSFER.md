# Technical Knowledge Transfer - Labour Chowk

**Target Audience**: Developers joining the project  
**Read Time**: 5-10 minutes  
**Last Updated**: 2025-12-17

---

## 🎯 Project Overview

**Labour Chowk** is a Laravel 11 MVP that connects hirers with daily labourers. It's a simple, mobile-first platform with no payments, ratings, chat, or GPS tracking.

**Core Flow**: Hirer selects area → sees available labourers → calls directly via phone.

---

## 🏗 Architecture Overview

```
┌─────────────────────────────────────────────────────────┐
│                    Public Routes                         │
│  / (welcome)  /browse  /login                            │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│              BrowseController                            │
│  - Filters by area, skills, name                        │
│  - Returns paginated labourers available today           │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│              Admin Routes (/admin/*)                     │
│  - Protected by auth + AdminOnly middleware              │
│  - Labourers, Availability, Areas, Skills CRUD          │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│                    Database Layer                        │
│  - Eloquent Models with relationships                    │
│  - Migrations define schema                              │
└─────────────────────────────────────────────────────────┘
```

---

## 📊 Database Schema

### Core Tables

```
users
├── id
├── name, email, password
└── is_admin (boolean) ──┐
                        │
areas ──────────────────┤
├── id                   │
├── name                 │
└── is_active            │
                        │
skills ─────────────────┤
├── id                   │
├── name                 │
└── is_active            │
                        │
labourers ───────────────┤
├── id                   │
├── full_name            │
├── phone_e164           │
├── area_id ─────────────┘
├── photo_path (nullable)
└── is_active

labourer_skill (pivot)
├── labourer_id
└── skill_id

availabilities
├── id
├── labourer_id
├── date (date, indexed)
└── status ('available')
```

### Key Relationships

- `Labourer` belongs to `Area`
- `Labourer` has many `Skill` (many-to-many via `labourer_skill`)
- `Labourer` has many `Availability` (one per date)
- `Availability` belongs to `Labourer`

**Important**: Availability is **date-keyed**. Each day starts empty; admin marks labourers available daily.

---

## 🔑 Key Components

### 1. Models (`app/Models/`)

**Labourer.php**
- Main entity representing a labourer
- Relationships: `area()`, `skills()`, `availabilities()`
- Photo stored in `storage/app/public/labourers/`

**Availability.php**
- Tracks daily availability status
- Composite unique key: `(labourer_id, date)`
- Status: `'available'` (extensible for future states)

**Area.php & Skill.php**
- Simple lookup tables with `is_active` flag
- Used for filtering and categorization

### 2. Controllers

**BrowseController** (`app/Http/Controllers/BrowseController.php`)
- Public-facing search/filter logic
- Two queries:
  - `$results`: Filtered by area (when selected)
  - `$allAvailable`: All available today (always shown)
- Uses eager loading to avoid N+1 queries

**Admin Controllers** (`app/Http/Controllers/Admin/`)
- `LabourerController`: Full CRUD with photo upload
- `AvailabilityTodayController`: Bulk mark/remove availability
- `AreaController` & `SkillController`: Simple CRUD

### 3. Middleware

**AdminOnly** (`app/Http/Middleware/AdminOnly.php`)
- Checks `auth()->user()->is_admin === true`
- Applied to all `/admin/*` routes
- Returns 403 if not admin

### 4. Authentication

**Login Flow**:
1. User logs in via Breeze (`/login`)
2. `AuthenticatedSessionController` checks `is_admin`
3. Admin → redirects to `/admin/labourers`
4. Non-admin → redirects to `/dashboard`

**Registration**: Disabled (admin-managed onboarding)

---

## 🎨 Frontend Structure

### Views

**Public**:
- `welcome.blade.php`: Landing page explaining purpose
- `browse/index.blade.php`: Search form + results
- `browse/_labourer-card.blade.php`: Reusable card component

**Admin**:
- `admin/layout.blade.php`: Admin shell with nav
- `admin/labourers/*`: CRUD views
- `admin/availability/today.blade.php`: Bulk availability management

### Styling

- **Tailwind CSS** (utility-first)
- **Mobile-first**: All layouts work on small screens
- **Default Avatars**: UI Avatars API for labourers without photos

---

## 🔄 Key Flows

### Flow 1: Hirer Browsing

```
1. User visits /browse
2. Selects area (required)
3. Optionally filters by skills/name
4. Clicks "Show available today"
5. Sees filtered results + all available below
6. Clicks "Call now" → tel: link opens dialer
```

### Flow 2: Admin Managing Availability

```
1. Admin logs in → redirected to /admin/labourers
2. Navigates to /admin/availability/today
3. Selects labourers
4. Clicks "Mark available" or "Remove"
5. Uses upsert: Availability::updateOrCreate([labourer_id, date])
6. Public browse immediately reflects changes
```

### Flow 3: Daily Reset

```
1. New day starts (midnight)
2. Availability table has no rows for new date
3. Admin must mark labourers available each morning
4. Optional: Cron runs app:prune-old-availability (cleans old records)
```

---

## 🛠 Important Patterns

### 1. Date-Keyed Availability

```php
// Always query by date
$today = CarbonImmutable::today()->toDateString();
$labourer->availabilities()->whereDate('date', $today)->exists();

// Upsert pattern
Availability::updateOrCreate(
    ['labourer_id' => $id, 'date' => $today],
    ['status' => 'available']
);
```

**Why**: Each day is independent; no complex state management.

### 2. Photo Handling

```php
// Upload
$path = $request->file('photo')->store('labourers', 'public');
$labourer->photo_path = $path;

// Display
@if($labourer->photo_path)
    <img src="{{ asset('storage/'.$labourer->photo_path) }}" />
@else
    <img src="https://ui-avatars.com/api/?name=..." />
@endif
```

**Storage**: `storage/app/public/labourers/` → symlinked to `public/storage/`

### 3. Admin Protection

```php
// Route middleware
Route::prefix('admin')->middleware(['auth', 'admin'])->group(...);

// Middleware check
if (!$user->is_admin) {
    abort(403);
}
```

### 4. Eager Loading (Performance)

```php
// Avoid N+1
Labourer::with(['area', 'skills', 'availabilities'])->get();

// Query availability for today
->whereHas('availabilities', fn($q) => 
    $q->whereDate('date', $today)->where('status', 'available')
)
```

---

## 📁 File Organization

```
app/
├── Console/Commands/
│   └── PruneOldAvailability.php    # Scheduled cleanup
├── Http/
│   ├── Controllers/
│   │   ├── BrowseController.php    # Public browse
│   │   └── Admin/                   # All admin controllers
│   ├── Middleware/
│   │   └── AdminOnly.php            # Admin gate
│   └── Requests/                    # Form validation
└── Models/                          # Eloquent models

database/
├── factories/                       # Model factories (for seeding)
├── migrations/                      # Schema definitions
└── seeders/                         # Demo data

resources/views/
├── admin/                           # Admin Blade templates
├── browse/                          # Public browse templates
└── welcome.blade.php                # Landing page

routes/
├── web.php                          # Main routes
├── auth.php                         # Auth routes (login, logout)
└── console.php                      # Scheduled commands
```

---

## 🔧 Common Tasks

### Adding a New Admin Page

1. Create controller in `app/Http/Controllers/Admin/`
2. Add route in `routes/web.php` (inside `admin` prefix group)
3. Create view in `resources/views/admin/`
4. Add nav link in `resources/views/admin/layout.blade.php`

### Modifying Labourer Model

1. Create migration: `php artisan make:migration add_field_to_labourers`
2. Update `Labourer` model `$fillable` array
3. Update form requests if needed
4. Update views to display/edit new field

### Adding a New Filter

1. Add query parameter in `BrowseController`
2. Add filter logic in query builder
3. Update browse form to include new filter
4. Pass filter value to view

### Seeding New Data

1. Update seeder in `database/seeders/`
2. Use factories: `Labourer::factory()->count(10)->create()`
3. Run: `php artisan migrate:fresh --seed`

---

## ⚠️ Important Constraints

1. **No Payments**: Platform only facilitates contact
2. **No Ratings**: Simple contact info only
3. **No Chat**: Phone calls only (`tel:` links)
4. **No GPS**: Areas are admin-maintained lists
5. **Admin-Managed**: All labourer creation is admin-only

---

## 🚀 Deployment Checklist

- [ ] Run `php artisan storage:link` (for photos)
- [ ] Set `APP_ENV=production` and `APP_DEBUG=false`
- [ ] Configure database credentials
- [ ] Set up cron for scheduler (optional): `* * * * * php artisan schedule:run`
- [ ] Change default admin password
- [ ] Ensure `storage/` and `bootstrap/cache/` are writable

---

## 📚 Quick Reference

### Routes

| Route | Controller | Purpose |
|-------|-----------|---------|
| `/` | Welcome | Landing page |
| `/browse` | BrowseController | Public search |
| `/login` | AuthenticatedSessionController | Admin login |
| `/admin/labourers` | LabourerController | CRUD labourers |
| `/admin/availability/today` | AvailabilityTodayController | Manage today's availability |
| `/admin/areas` | AreaController | Manage areas |
| `/admin/skills` | SkillController | Manage skills |

### Key Commands

```bash
php artisan migrate:fresh --seed    # Reset DB + seed demo data
php artisan storage:link            # Link storage for photos
php artisan schedule:list           # View scheduled tasks
php artisan app:prune-old-availability --days=30  # Clean old records
```

### Default Credentials

- **Email**: `admin@labourchowk.test`
- **Password**: `password`

**⚠️ Change in production!**

---

## 🐛 Troubleshooting

**No photos showing?**
- Run `php artisan storage:link`
- Check `storage/app/public/labourers/` exists
- Verify file permissions

**Admin can't access routes?**
- Check `users.is_admin = 1` in database
- Verify `AdminOnly` middleware is registered in `bootstrap/app.php`

**Availability not showing?**
- Check `availabilities` table has rows for today's date
- Verify `status = 'available'`
- Check labourer `is_active = 1`

**N+1 query issues?**
- Add `->with(['area', 'skills'])` to queries
- Check Laravel Debugbar for query count

---

## 📖 Further Reading

- **Specifications**: `/specs/001-today-labour-discovery/`
- **Implementation Plan**: `/specs/001-today-labour-discovery/plan.md`
- **Data Model**: `/specs/001-today-labour-discovery/data-model.md`
- **Tasks**: `/specs/001-today-labour-discovery/tasks.md`
- **README**: `/README.md` (user-facing documentation)

---

**Questions?** Check the specs folder or review the code comments. The codebase follows Laravel conventions and is well-structured for easy navigation.

