# Understanding `admin-layout.blade.php` Component

## 📋 Overview

`admin-layout.blade.php` is a **Blade component** that provides a consistent layout wrapper for all admin pages. It's a thin wrapper that delegates to the actual layout file.

---

## 🔄 How It Works (Flow Diagram)

```
┌─────────────────────────────────────────────────────────┐
│  Admin View (e.g., labourers/index.blade.php)           │
│                                                           │
│  <x-admin-layout title="Labourers" subtitle="...">       │
│      <!-- Page content here -->                          │
│  </x-admin-layout>                                       │
└─────────────────────────────────────────────────────────┘
                    │
                    ▼
┌─────────────────────────────────────────────────────────┐
│  Component: admin-layout.blade.php                       │
│  (resources/views/components/admin-layout.blade.php)     │
│                                                           │
│  1. Receives props: title, subtitle                       │
│  2. Receives slot: page content                         │
│  3. Passes everything to admin.layout                    │
└─────────────────────────────────────────────────────────┘
                    │
                    ▼
┌─────────────────────────────────────────────────────────┐
│  Layout File: admin/layout.blade.php                     │
│  (resources/views/admin/layout.blade.php)                 │
│                                                           │
│  - HTML structure (DOCTYPE, head, body)                 │
│  - Header with title/subtitle                            │
│  - Navigation menu (Labourers, Availability, etc.)      │
│  - Logout button                                          │
│  - Status messages                                        │
│  - {{ $slot }} - where page content is inserted          │
└─────────────────────────────────────────────────────────┘
```

---

## 📍 Where It's Called

The component is used in **all admin views**:

1. **`admin/labourers/index.blade.php`**
   ```blade
   <x-admin-layout title="Labourers" subtitle="Search, edit, and manage labourer profiles">
       <!-- Labourer list content -->
   </x-admin-layout>
   ```

2. **`admin/labourers/create.blade.php`**
   ```blade
   <x-admin-layout title="Add labourer" subtitle="Create a new labourer profile">
       <!-- Create form -->
   </x-admin-layout>
   ```

3. **`admin/labourers/edit.blade.php`**
4. **`admin/availability/today.blade.php`**
5. **`admin/areas/index.blade.php`**
6. **`admin/skills/index.blade.php`**

---

## 🎯 Purpose & Benefits

### 1. **Consistent Admin UI**
   - All admin pages share the same header, navigation, and styling
   - Ensures uniform look and feel across admin section

### 2. **DRY Principle (Don't Repeat Yourself)**
   - Header HTML, navigation menu, logout button defined once
   - No need to copy-paste layout code in each admin view

### 3. **Easy Maintenance**
   - Change navigation? Update `admin/layout.blade.php` once
   - All admin pages automatically reflect changes

### 4. **Component-Based Architecture**
   - Uses Laravel's Blade component system (`<x-component-name>`)
   - Clean, reusable pattern

---

## 🔍 Code Breakdown

### Step 1: Component Definition (`admin-layout.blade.php`)

```blade
@props(['title' => null, 'subtitle' => null])
```
- Defines component props (attributes you can pass)
- `title` and `subtitle` are optional with default `null`

```blade
@php($title = $title ?? 'Admin')
```
- Sets default title if not provided

```blade
@include('admin.layout', [
    'title' => $title,
    'subtitle' => $subtitle,
    'slot' => $slot,
])
```
- **This is the key part!**
- Includes the actual layout file (`admin/layout.blade.php`)
- Passes `title`, `subtitle`, and `$slot` (the page content)

### Step 2: Usage in Admin Views

```blade
<x-admin-layout title="Labourers" subtitle="Manage profiles">
    <h1>My Page Content</h1>
    <p>This content goes into {{ $slot }}</p>
</x-admin-layout>
```

**What happens:**
1. Laravel finds `resources/views/components/admin-layout.blade.php`
2. Component receives `title="Labourers"` and `subtitle="Manage profiles"`
3. Component receives the content between tags as `$slot`
4. Component includes `admin/layout.blade.php` and passes everything
5. Layout file renders HTML structure and inserts `$slot` content

### Step 3: Layout File (`admin/layout.blade.php`)

```blade
<title>{{ $title ?? 'Admin' }} · Labour Chowk</title>
```
- Uses the `$title` prop in page title

```blade
<div class="text-sm font-semibold truncate">Labour Chowk · Admin</div>
<div class="text-xs text-gray-600 truncate">{{ $subtitle ?? '' }}</div>
```
- Displays title and subtitle in header

```blade
<nav class="mx-auto max-w-3xl px-4 pb-3">
    <a href="{{ route('admin.labourers.index') }}">Labourers</a>
    <!-- More nav links -->
</nav>
```
- Navigation menu (same on all admin pages)

```blade
<main id="main" class="mx-auto max-w-3xl px-4 py-6">
    {{ $slot }}
</main>
```
- **`{{ $slot }}`** is where your page content is inserted!

---

## 💡 Why Two Files?

**Question**: Why have both `admin-layout.blade.php` (component) and `admin/layout.blade.php` (layout)?

**Answer**: This is a **wrapper pattern**:

1. **Component** (`admin-layout.blade.php`):
   - Thin wrapper for Blade component system
   - Handles props and passes to include
   - Allows `<x-admin-layout>` syntax

2. **Layout** (`admin/layout.blade.php`):
   - Actual HTML structure
   - Could be included directly, but component pattern is cleaner
   - Separates component logic from layout structure

**Alternative approach** (if you wanted to simplify):
```blade
<!-- In admin views, you could do: -->
@include('admin.layout', ['title' => 'Labourers', 'subtitle' => '...'])
```

But using `<x-admin-layout>` is more Laravel-idiomatic and cleaner.

---

## 🛠 Customization

### Adding a New Prop

1. Update component:
   ```blade
   @props(['title' => null, 'subtitle' => null, 'showBreadcrumb' => false])
   ```

2. Pass to layout:
   ```blade
   @include('admin.layout', [
       'title' => $title,
       'subtitle' => $subtitle,
       'showBreadcrumb' => $showBreadcrumb,
       'slot' => $slot,
   ])
   ```

3. Use in layout:
   ```blade
   @if($showBreadcrumb)
       <!-- Breadcrumb code -->
   @endif
   ```

### Adding Navigation Link

Edit `admin/layout.blade.php`:
```blade
<a href="{{ route('admin.new-section.index') }}">New Section</a>
```

All admin pages automatically get the new link!

---

## 📝 Summary

| Aspect | Details |
|--------|---------|
| **File Location** | `resources/views/components/admin-layout.blade.php` |
| **Type** | Blade Component |
| **Usage** | `<x-admin-layout title="..." subtitle="...">content</x-admin-layout>` |
| **Purpose** | Wrapper that provides consistent admin page layout |
| **Delegates To** | `resources/views/admin/layout.blade.php` |
| **Used In** | All admin views (labourers, availability, areas, skills) |
| **Key Feature** | `{{ $slot }}` in layout file inserts page content |

---

## 🎓 Key Takeaways

1. **Component Syntax**: `<x-admin-layout>` automatically looks for `components/admin-layout.blade.php`
2. **Props**: Pass attributes like `title="..."` to customize per page
3. **Slot**: Content between tags becomes `$slot` variable
4. **Include**: Component includes actual layout file and passes data
5. **DRY**: One layout file, many pages use it

This pattern ensures all admin pages have consistent navigation, header, and styling while allowing each page to customize title/subtitle.

