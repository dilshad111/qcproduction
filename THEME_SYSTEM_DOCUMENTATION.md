# Multi-Theme System Documentation

## Overview
The CORRUGO MIS application now supports three premium admin themes that users can switch between based on their preference:

1. **Star Admin 2** - Modern Bootstrap Admin Template (Default)
2. **Soft UI Dashboard** - Premium Bootstrap 5 Admin with glassmorphism design
3. **Sneat Admin** - Bootstrap 5 Laravel Template with clean interface

## Features

### Theme Switching
- Users can switch themes from the navbar dropdown menu (palette icon)
- Theme preference is saved per user in the database
- Theme changes take effect immediately upon selection
- Each theme has a unique visual identity with gradient indicators

### Theme Layouts

#### 1. Star Admin 2 (Default)
- **File**: `resources/views/layouts/staradmin.blade.php`
- **Theme Value**: `staradmin`
- **Design**: Modern Bootstrap with customizable color schemes
- **Sidebar**: Vertical navigation with icon-based menu
- **Features**: 
  - Multiple color variants (light, dark, blue, sunset, forest, glass)
  - Glassmorphism effects
  - Smooth animations and transitions

#### 2. Soft UI Dashboard
- **File**: `resources/views/layouts/softui.blade.php`
- **Theme Value**: `softui`
- **Design**: Premium soft UI with gradient backgrounds
- **Sidebar**: Purple-pink gradient vertical menu
- **Features**:
  - Soft shadows and rounded corners
  - Glassmorphism navbar
  - Modern typography (Open Sans)
  - Smooth hover effects

#### 3. Sneat Admin
- **File**: `resources/views/layouts/sneat.blade.php`
- **Theme Value**: `sneat`
- **Design**: Clean Bootstrap 5 interface
- **Sidebar**: Purple gradient with Boxicons
- **Features**:
  - Professional layout structure
  - Responsive design
  - Modern icon set (Boxicons)
  - Clean typography (Public Sans)

## Technical Implementation

### Database Schema
```sql
ALTER TABLE users ADD COLUMN theme VARCHAR(255) DEFAULT 'staradmin';
```

### Theme Controller
**File**: `app/Http/Controllers/ThemeController.php`

```php
public function update(Request $request)
{
    $request->validate([
        'theme' => 'required|in:staradmin,softui,sneat'
    ]);

    $user = auth()->user();
    if ($user) {
        $user->update(['theme' => $request->theme]);
    }

    return back()->with('success', 'Theme updated successfully!');
}
```

### Dynamic Layout Loader
**File**: `resources/views/layouts/app.blade.php`

The main app layout now acts as a theme switcher that dynamically loads the appropriate layout based on user preference:

```php
@php
    $userTheme = Auth::check() ? (Auth::user()->theme ?? 'staradmin') : 'staradmin';
    
    $themeLayouts = [
        'staradmin' => 'layouts.staradmin',
        'softui' => 'layouts.softui',
        'sneat' => 'layouts.sneat',
    ];
    
    $selectedLayout = $themeLayouts[$userTheme] ?? 'layouts.staradmin';
@endphp

@extends($selectedLayout)
```

### Routes
```php
Route::post('theme/update', [App\Http\Controllers\ThemeController::class, 'update'])
    ->name('theme.update');
```

## Theme Selector UI

The theme selector appears in the navbar with:
- **Icon**: Palette icon (fa-palette)
- **Dropdown**: Shows all three themes with:
  - Gradient color indicator
  - Theme name
  - Description
  - Active checkmark for current theme

### Theme Indicators
- **Star Admin 2**: Purple-violet gradient (#667eea → #764ba2)
- **Soft UI Dashboard**: Purple-pink gradient (#7928CA → #FF0080)
- **Sneat Admin**: Blue-purple gradient (#696cff → #5f61e6)

## Usage

### For Users
1. Click the palette icon in the navbar
2. Select your preferred theme from the dropdown
3. The page will reload with the new theme applied
4. Your preference is saved automatically

### For Developers

#### Adding a New Theme
1. Create a new layout file in `resources/views/layouts/[theme-name].blade.php`
2. Update `ThemeController.php` validation to include the new theme
3. Add the theme to the `$themeLayouts` array in `app.blade.php`
4. Add the theme option to the dropdown menu in all layout files

#### Customizing Existing Themes
- **Star Admin**: Modify `resources/views/layouts/staradmin.blade.php`
- **Soft UI**: Modify `resources/views/layouts/softui.blade.php`
- **Sneat**: Modify `resources/views/layouts/sneat.blade.php`

## CDN Assets

### Soft UI Dashboard
- CSS: `https://cdn.jsdelivr.net/npm/soft-ui-dashboard@1.0.7/assets/css/soft-ui-dashboard.min.css`
- JS: `https://cdn.jsdelivr.net/npm/soft-ui-dashboard@1.0.7/assets/js/soft-ui-dashboard.min.js`

### Sneat Admin
- Theme CSS: `https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template-free/assets/vendor/css/theme-default.css`
- Demo CSS: `https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template-free/assets/css/demo.css`
- Menu JS: `https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template-free/assets/vendor/js/menu.js`

### Star Admin 2
- CSS: `https://cdn.jsdelivr.net/gh/BootstrapDash/star-admin2-free-admin-template@main/src/assets/css/vertical-layout-light/style.min.css`
- JS: `https://cdn.jsdelivr.net/gh/BootstrapDash/star-admin2-free-admin-template@main/src/assets/js/template.js`

## Migration

### Running the Migration
```bash
php artisan migrate
```

This will add the `theme` column to the `users` table with a default value of `staradmin`.

### Updating Existing Users
Existing users will automatically get the default theme (`staradmin`). They can change it from the theme selector.

## Troubleshooting

### Theme Not Changing
1. Clear browser cache
2. Check if user is authenticated
3. Verify theme value in database: `SELECT theme FROM users WHERE id = [user_id]`

### Layout Issues
1. Check browser console for CSS/JS loading errors
2. Verify CDN links are accessible
3. Clear Laravel cache: `php artisan cache:clear`

### Missing Menu Items
- Ensure menu permissions are set correctly in the User model
- Check if the menu items are included in all three layout files

## Future Enhancements

Potential improvements:
- Add theme preview before selection
- Custom color picker for Star Admin theme
- Dark mode variants for all themes
- Theme-specific dashboard widgets
- Export/import theme settings
- Company-wide default theme setting

## Support

For issues or questions:
- Contact: 0300-2566358
- Developer: SACHAAN TECH SOL.
