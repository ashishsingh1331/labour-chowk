# Labour Chowk - Daily Labour Finder

A Laravel-based MVP application that helps hirers quickly discover available labourers in specific areas for "today" and contact them immediately via phone call.

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Setup Instructions](#setup-instructions)
- [Admin Credentials](#admin-credentials)
- [Testing Guide](#testing-guide)
- [Project Structure](#project-structure)
- [Deployment Notes](#deployment-notes)

## 🎯 Overview

**Problem**: In many areas, finding daily labour is difficult. Contractors and individuals have to go to the chowk early in the morning and find labour manually. Labourers also waste time waiting without guarantee of work.

**Solution**: Labour Chowk helps hirers quickly discover available labourers in a specific area for **today** and contact them immediately via phone call.

### Key Principles

- **Mobile-First**: Primary UX works end-to-end on small screens
- **Accessibility**: Readable fonts, high contrast, minimal steps
- **Tight Scope**: No payments, no ratings, no chat, no GPS tracking
- **Admin-Managed**: All labourer onboarding is managed by administrators
- **Performance**: Fast loading and responsive UI

## ✨ Features

### Public Features (Hirers)

1. **Welcome Page** (`/`)
   - Explains project purpose and how it works
   - Lists what the platform does and doesn't do
   - Direct link to browse page

2. **Browse & Search** (`/browse`)
   - Filter by area (required)
   - Optional: Search by name
   - Optional: Filter by skills
   - View all available labourers for today
   - Direct "Call now" button (tel: links)

3. **Listing View**
   - Shows all labourers available today below the search form
   - Displays area, skills, and contact information
   - Paginated results

### Admin Features

1. **Authentication**
   - Admin login page
   - Session-based authentication
   - Admin-only middleware protection

2. **Labourer Management** (`/admin/labourers`)
   - Create, read, update, delete labourers
   - Upload photos
   - Assign areas and skills
   - Toggle active/inactive status
   - Search and filter by area

3. **Availability Management** (`/admin/availability/today`)
   - View all labourers with today's availability status
   - Bulk mark labourers as available
   - Remove availability for today
   - Filter by area and skills

4. **Area Management** (`/admin/areas`)
   - Add new areas (chowk/locality names)
   - Toggle active/inactive status
   - Simple list view

5. **Skill Management** (`/admin/skills`)
   - Add new skills (work types)
   - Toggle active/inactive status
   - Simple list view

### Data Features

- **Default Avatars**: Labourers without photos get auto-generated avatars with initials
- **Daily Availability**: Availability is date-keyed; new days start empty automatically
- **Seeded Data**: Demo data includes areas, skills, labourers, and today's availability

## 🛠 Tech Stack

- **Framework**: Laravel 11.x
- **PHP**: 8.3+
- **Database**: MySQL (production) / SQLite (local development)
- **Authentication**: Laravel Breeze (Blade)
- **Frontend**: Tailwind CSS, Vite
- **Storage**: Laravel Filesystem (public disk for photos)

## 🚀 Setup Instructions

### Prerequisites

- PHP 8.3 or higher
- Composer
- Node.js and NPM
- MySQL (or SQLite for local)

### Installation Steps

1. **Clone the repository** (if not already done)
   ```bash
   git clone <repository-url>
   cd labour-chowk
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database** (edit `.env`)
   ```env
   DB_CONNECTION=mysql  # or sqlite for local
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=labour_chowk
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

   For SQLite (local development):
   ```env
   DB_CONNECTION=sqlite
   # DB_DATABASE=  # Comment out or leave empty
   ```

5. **Run migrations and seed data**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Create storage link** (for photos)
   ```bash
   php artisan storage:link
   ```

7. **Install and build frontend assets**
   ```bash
   npm install
   npm run build
   # Or for development: npm run dev
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

9. **Access the application**
   - Public site: `http://127.0.0.1:8000`
   - Admin login: `http://127.0.0.1:8000/login`

## 🔐 Admin Credentials

After seeding, use these credentials to log in:

- **Email**: `admin@labourchowk.test`
- **Password**: `password`

⚠️ **Important**: Change these credentials in production!

## 🧪 Testing Guide

Follow these steps to verify all functionality:

### Step 1: Welcome Page

1. Visit `http://127.0.0.1:8000/`
2. **Verify**:
   - ✅ Welcome page displays project explanation
   - ✅ "How it works" section shows 4 steps
   - ✅ "What we don't do" section lists exclusions
   - ✅ "Find labourers available today →" button is visible
   - ✅ Page is mobile-responsive

### Step 2: Public Browse Page

1. Click "Find labourers available today →" or visit `/browse`
2. **Verify**:
   - ✅ Search form displays with area dropdown (required)
   - ✅ Optional name search field
   - ✅ Optional skills checkboxes
   - ✅ "Show available today" button

3. **Test without filters**:
   - ✅ Scroll down to see "All Available Today" section
   - ✅ Labourer cards display with photos/avatars
   - ✅ Each card shows: name, area, skills, call button
   - ✅ Pagination works if many results

4. **Test with area filter**:
   - Select an area from dropdown
   - Click "Show available today"
   - ✅ "Filtered Results" section appears
   - ✅ Only labourers from selected area show
   - ✅ Cards display correctly

5. **Test with name search**:
   - Select an area
   - Enter a name in search field
   - ✅ Results filter by name

6. **Test with skills filter**:
   - Select an area
   - Check one or more skills
   - ✅ Results filter by selected skills

7. **Test call button**:
   - Click "Call now" on any labourer card
   - ✅ Phone dialer opens (on mobile) or shows tel: link

8. **Test empty state**:
   - Select an area with no available labourers
   - ✅ "No labourers are marked available today" message shows

### Step 3: Admin Login

1. Visit `/login` or click admin link
2. **Verify**:
   - ✅ Login form displays
   - ✅ Email and password fields are present
   - ✅ "Remember me" checkbox works
   - ✅ "Forgot password" link (if enabled)

3. **Test login**:
   - Enter admin credentials
   - ✅ Redirects to admin dashboard
   - ✅ Admin navigation menu appears

4. **Test logout**:
   - Click "Logout" button
   - ✅ Redirects to login page
   - ✅ Session cleared

### Step 4: Admin - Labourer Management

1. Navigate to `/admin/labourers`
2. **Verify list view**:
   - ✅ All labourers display in cards
   - ✅ Photos/avatars show correctly
   - ✅ Name, phone, area, skills visible
   - ✅ Active/Inactive status badges
   - ✅ Search and area filter work
   - ✅ Pagination works

3. **Test create labourer**:
   - Click "Add labourer"
   - Fill in: name, phone (+91 format), area, skills
   - Upload a photo (optional)
   - Toggle active status
   - Click "Save"
   - ✅ Redirects to edit page
   - ✅ Success message shows
   - ✅ New labourer appears in list

4. **Test edit labourer**:
   - Click "Edit" on any labourer
   - ✅ Edit form loads with current data
   - Modify name, skills, or photo
   - Click "Save changes"
   - ✅ Success message shows
   - ✅ Changes reflected in list

5. **Test delete labourer**:
   - Click "Edit" on a labourer
   - Scroll to bottom
   - Click "Delete labourer"
   - Confirm deletion
   - ✅ Labourer removed from list
   - ✅ Success message shows

6. **Test photo upload**:
   - Edit a labourer without photo
   - Upload an image file
   - ✅ Photo displays after save
   - ✅ Default avatar shows if no photo

### Step 5: Admin - Availability Management

1. Navigate to `/admin/availability/today`
2. **Verify dashboard**:
   - ✅ List of all labourers with availability status
   - ✅ Filter by area and skills
   - ✅ "Available" and "Not available" sections

3. **Test mark available**:
   - Select one or more labourers
   - Click "Mark available"
   - ✅ Selected labourers move to "Available" section
   - ✅ Success message shows

4. **Test remove availability**:
   - Select available labourers
   - Click "Remove"
   - ✅ Labourers move to "Not available" section
   - ✅ Success message shows

5. **Verify public impact**:
   - Mark a labourer as available
   - Go to public browse page
   - Select their area
   - ✅ Labourer appears in results

### Step 6: Admin - Area Management

1. Navigate to `/admin/areas`
2. **Verify list**:
   - ✅ All areas display
   - ✅ Active/Inactive status visible

3. **Test add area**:
   - Enter area name (e.g., "Sector 12 Chowk")
   - Click "Add"
   - ✅ New area appears in list
   - ✅ Success message shows

4. **Test toggle status**:
   - Click toggle on an area
   - ✅ Status changes (Active ↔ Inactive)
   - ✅ Inactive areas don't show in public browse

### Step 7: Admin - Skill Management

1. Navigate to `/admin/skills`
2. **Verify list**:
   - ✅ All skills display
   - ✅ Active/Inactive status visible

3. **Test add skill**:
   - Enter skill name (e.g., "Masonry")
   - Click "Add"
   - ✅ New skill appears in list
   - ✅ Success message shows

4. **Test toggle status**:
   - Click toggle on a skill
   - ✅ Status changes (Active ↔ Inactive)
   - ✅ Inactive skills don't show in public browse

### Step 8: Mobile Responsiveness

1. Open browser DevTools (F12)
2. Toggle device toolbar (mobile view)
3. **Test on small screen** (375px width):
   - ✅ Welcome page is readable
   - ✅ Browse form fits without horizontal scroll
   - ✅ Labourer cards stack vertically
   - ✅ Call buttons are easily tappable
   - ✅ Admin pages are usable
   - ✅ Navigation menus work

### Step 9: Default Avatars

1. Create a labourer without uploading a photo
2. **Verify**:
   - ✅ Default avatar with initials displays
   - ✅ Avatar has colored background
   - ✅ Shows in browse page
   - ✅ Shows in admin list

### Step 10: Daily Availability Reset

1. Note: Availability is date-keyed
2. **Verify**:
   - ✅ New day starts with empty availability
   - ✅ Admin must mark labourers available each day
   - ✅ Old availability records can be pruned (optional cron)

## 📁 Project Structure

```
labour-chowk/
├── app/
│   ├── Console/Commands/        # Artisan commands (prune availability)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin controllers
│   │   │   └── BrowseController.php
│   │   ├── Middleware/         # AdminOnly middleware
│   │   └── Requests/           # Form validation
│   └── Models/                 # Eloquent models
├── database/
│   ├── factories/              # Model factories
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── resources/
│   ├── views/
│   │   ├── admin/              # Admin Blade templates
│   │   ├── browse/             # Public browse templates
│   │   └── welcome.blade.php   # Welcome page
│   └── css/app.css             # Tailwind CSS
├── routes/
│   ├── web.php                 # Web routes
│   ├── auth.php                # Auth routes
│   └── console.php             # Scheduled commands
└── specs/                      # Project specifications
```

## 🚢 Deployment Notes

### Storage

- Run `php artisan storage:link` on the server
- Ensure `storage/` and `bootstrap/cache/` are writable

### Database

- Run migrations: `php artisan migrate`
- Seed initial data: `php artisan db:seed` (optional)

### Scheduler (Optional)

For automatic cleanup of old availability records:

1. Add to server crontab:
   ```bash
   * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
   ```

2. The scheduler runs `app:prune-old-availability --days=30` daily

### Security

- Change default admin credentials
- Ensure `.env` is not committed
- Admin routes are protected by `AdminOnly` middleware
- Self-serve registration is disabled

### Environment Variables

Key variables in `.env`:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=labour_chowk
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## 📝 Notes

- **No Payments**: Platform only facilitates contact; no booking or payment system
- **No Ratings**: Simple contact information only
- **No Chat**: Phone calls only
- **No GPS**: Areas are admin-maintained lists
- **Admin-Managed**: All labourer onboarding is done by administrators

## 🤝 Contributing

This is an MVP project. For contributions or issues, please refer to the project specifications in `/specs/001-today-labour-discovery/`.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

**Last Updated**: 2025-12-17
**Version**: MVP 1.0
