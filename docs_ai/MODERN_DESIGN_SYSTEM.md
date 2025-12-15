# Modern Design System Documentation for 2Earn.cash

## Overview
This document describes the enhanced design system implemented across the 2Earn.cash platform, providing modern, accessible, and consistent UI components with full RTL support.

## Design Tokens

### Color Palette

#### Primary Colors
- **Primary**: Blue shades (#009fe3) - Main brand color for primary actions
- **Secondary**: Teal shades (#5ea3cb) - Supporting brand color
- **Accent**: Orange shades (#f59b33) - Attention-grabbing elements

#### Status Colors
- **Success**: Green (#42c784) - Positive actions and confirmations
- **Warning**: Yellow (#ffc107) - Cautions and warnings
- **Danger**: Red (#f41f31) - Errors and destructive actions
- **Info**: Light blue (#17a3df) - Informational messages

#### Neutral Colors
- **Gray Scale**: 50-900 - Text, borders, backgrounds

### Typography

#### Font Families
- **Heading**: Poppins - Used for headings and titles
- **Body**: IBM Plex Sans - Used for body text and UI elements
- **Fallback**: hkgrotesk, system-ui, sans-serif

#### Font Sizes
- xs: 0.75rem (12px)
- sm: 0.875rem (14px)
- base: 1rem (16px)
- lg: 1.125rem (18px)
- xl: 1.25rem (20px)
- 2xl-5xl: Progressive larger sizes

### Spacing
Consistent spacing scale from 0 to 144 (in 0.25rem increments)
Extended with: 18, 22, 26, 30, 128, 144

### Border Radius
- sm: 0.25rem
- default: 0.375rem
- md: 0.5rem
- lg: 0.75rem
- xl: 1rem
- 2xl: 1.5rem
- 3xl: 2rem
- full: 9999px

### Shadows
- Standard shadows: sm, default, md, lg, xl, 2xl
- Special effects: glow, glow-lg (for emphasis)
- Color-specific: shadow-primary, shadow-success, shadow-danger

## Component Classes

### Cards

#### Modern Card
```html
<div class="card-modern">
    <div class="card-modern-header">
        <h3>Card Title</h3>
    </div>
    <div class="card-modern-body">
        Content goes here
    </div>
    <div class="card-modern-footer">
        Footer content
    </div>
</div>
```

Features:
- Rounded corners (xl)
- Smooth shadow transitions
- Dark mode support
- Responsive padding

### Buttons

#### Button Variants
```html
<!-- Primary Button -->
<button class="btn-primary-modern">Primary Action</button>

<!-- Secondary Button -->
<button class="btn-secondary-modern">Secondary Action</button>

<!-- Success Button -->
<button class="btn-success-modern">Success Action</button>

<!-- Danger Button -->
<button class="btn-danger-modern">Danger Action</button>

<!-- Outline Button -->
<button class="btn-outline-modern">Outline Action</button>

<!-- Ghost Button -->
<button class="btn-ghost-modern">Ghost Action</button>
```

Features:
- Smooth hover effects with lift animation
- Icon support with gap spacing
- Disabled states
- Loading states compatible

### Forms

#### Input Fields
```html
<div>
    <label class="label-modern">Field Label</label>
    <input type="text" class="input-modern" placeholder="Enter text">
</div>

<!-- Error state -->
<input type="text" class="input-modern input-error" placeholder="Error state">

<!-- Success state -->
<input type="text" class="input-modern input-success" placeholder="Success state">
```

#### Select Fields
```html
<select class="select-modern">
    <option>Option 1</option>
    <option>Option 2</option>
</select>
```

Features:
- Consistent styling across browsers
- Custom dropdown arrow
- Focus states with ring effect
- Dark mode support

### Tables

#### Modern Table
```html
<table class="table-modern">
    <thead>
        <tr>
            <th>Header 1</th>
            <th>Header 2</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Data 1</td>
            <td>Data 2</td>
        </tr>
    </tbody>
</table>
```

Features:
- Hover effects on rows
- Consistent padding
- Dark mode support
- Responsive adjustments

### Badges

#### Badge Variants
```html
<span class="badge-primary">Primary</span>
<span class="badge-success">Success</span>
<span class="badge-warning">Warning</span>
<span class="badge-danger">Danger</span>
<span class="badge-info">Info</span>
<span class="badge-gray">Gray</span>
```

Features:
- Pill-shaped design
- Color-coded semantic meaning
- Dark mode variants

### Alerts

#### Alert Types
```html
<div class="alert-success">
    <i class="icon-check"></i>
    <div>Success message goes here</div>
</div>

<div class="alert-warning">
    <i class="icon-warning"></i>
    <div>Warning message goes here</div>
</div>

<div class="alert-danger">
    <i class="icon-error"></i>
    <div>Error message goes here</div>
</div>

<div class="alert-info">
    <i class="icon-info"></i>
    <div>Info message goes here</div>
</div>
```

### Modals

#### Modern Modal
```html
<div class="modal-modern">
    <div class="modal-backdrop"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Modal Title</h3>
        </div>
        <div class="modal-body">
            Modal content
        </div>
        <div class="modal-footer">
            <button class="btn-ghost-modern">Cancel</button>
            <button class="btn-primary-modern">Confirm</button>
        </div>
    </div>
</div>
```

Features:
- Backdrop blur effect
- Slide-up animation
- Responsive sizing
- Dark mode support

### Dropdowns

#### Modern Dropdown
```html
<div class="dropdown-modern">
    <a href="#" class="dropdown-item">
        <i class="icon"></i>
        <span>Item 1</span>
    </a>
    <div class="dropdown-divider"></div>
    <a href="#" class="dropdown-item">
        <i class="icon"></i>
        <span>Item 2</span>
    </a>
</div>
```

### Navigation

#### Modern Nav Tabs
```html
<nav class="nav-modern">
    <a href="#" class="nav-item nav-item-active">Tab 1</a>
    <a href="#" class="nav-item">Tab 2</a>
    <a href="#" class="nav-item">Tab 3</a>
</nav>
```

### Sidebar

#### Modern Sidebar
```html
<aside class="sidebar-modern">
    <a href="#" class="sidebar-item sidebar-item-active">
        <i class="icon"></i>
        <span>Active Item</span>
    </a>
    <a href="#" class="sidebar-item">
        <i class="icon"></i>
        <span>Regular Item</span>
    </a>
</aside>
```

### Pagination

#### Modern Pagination
```html
<div class="pagination-modern">
    <button class="page-item">Previous</button>
    <button class="page-item page-item-active">1</button>
    <button class="page-item">2</button>
    <button class="page-item">3</button>
    <button class="page-item">Next</button>
</div>
```

### Progress Bars

#### Modern Progress
```html
<div class="progress-modern">
    <div class="progress-bar" style="width: 60%"></div>
</div>
```

## Utility Classes

### Loading States

#### Skeleton Loader
```html
<div class="skeleton h-4 w-full"></div>
<div class="skeleton h-8 w-32 mt-2"></div>
```

#### Spinner
```html
<div class="spinner"></div>
```

### Special Effects

#### Glassmorphism
```html
<div class="glass p-6 rounded-xl">
    Glass effect content
</div>
```

#### Gradient Text
```html
<h1 class="gradient-text">Gradient Heading</h1>
```

#### Enhanced Shadows
```html
<div class="shadow-primary">Primary shadow effect</div>
<div class="shadow-success">Success shadow effect</div>
<div class="shadow-danger">Danger shadow effect</div>
```

### Scrollbar Styling
```html
<div class="scrollbar-thin overflow-auto">
    Long content that scrolls
</div>
```

## Animations

### Built-in Animations
- `animate-fade-in` - Fade in effect
- `animate-fade-out` - Fade out effect
- `animate-slide-in-right` - Slide from right
- `animate-slide-in-left` - Slide from left
- `animate-slide-up` - Slide from bottom
- `animate-slide-down` - Slide from top
- `animate-bounce-slow` - Slow bounce
- `animate-pulse-slow` - Slow pulse
- `animate-spin-slow` - Slow spin

### Animation Delays
```html
<div class="animate-fade-in animation-delay-100">Delayed 100ms</div>
<div class="animate-fade-in animation-delay-200">Delayed 200ms</div>
<div class="animate-fade-in animation-delay-300">Delayed 300ms</div>
```

## Background Gradients

### Predefined Gradients
```html
<div class="bg-gradient-primary">Primary gradient</div>
<div class="bg-gradient-success">Success gradient</div>
<div class="bg-gradient-danger">Danger gradient</div>
<div class="bg-gradient-info">Info gradient</div>
<div class="bg-gradient-warm">Warm gradient</div>
```

## Dark Mode

### Enabling Dark Mode
Dark mode is automatically applied when `data-layout-mode="dark"` attribute is present on the HTML element.

All components automatically adapt to dark mode with proper contrast and readability.

### Dark Mode Classes
Most components use Tailwind's `dark:` prefix for dark mode variants:
```html
<div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
    Content adapts to dark mode
</div>
```

## RTL Support

### RTL Mode
RTL (Right-to-Left) support is automatically enabled when the `dir="rtl"` attribute is present.

All components automatically flip layouts, adjust text alignment, and mirror spacing for RTL languages.

### RTL-Specific Adjustments
- Text alignment automatically reversed
- Margins and paddings flipped
- Icons and arrows mirrored
- Dropdown positioning adjusted
- Border radius corners swapped

## Responsive Design

### Breakpoints
- sm: 640px
- md: 768px
- lg: 1024px
- xl: 1280px
- 2xl: 1536px

### Mobile-First Approach
All components are designed mobile-first and scale up for larger screens:
```html
<div class="p-4 md:p-6 lg:p-8">
    Responsive padding
</div>
```

## Accessibility

### Focus States
All interactive elements have visible focus indicators with ring effects.

### Color Contrast
All color combinations meet WCAG AA standards for contrast ratios.

### Keyboard Navigation
All components support full keyboard navigation.

## Best Practices

1. **Use Semantic HTML**: Always use proper HTML5 semantic elements
2. **Leverage Utility Classes**: Combine utility classes instead of writing custom CSS
3. **Consistent Spacing**: Use the spacing scale (4, 8, 12, 16, 20, 24...)
4. **Color Usage**: Use semantic colors (primary, success, danger) appropriately
5. **Dark Mode**: Test all UIs in both light and dark modes
6. **RTL Testing**: Verify RTL layouts for Arabic language support
7. **Mobile First**: Design for mobile screens first, then enhance for desktop
8. **Performance**: Use `will-change` sparingly for animations
9. **Accessibility**: Always include proper ARIA labels and alt text

## Migration Guide

### Converting Existing Components

#### Old Style
```html
<div class="card">
    <div class="card-header">Title</div>
    <div class="card-body">Content</div>
</div>
```

#### New Modern Style
```html
<div class="card-modern">
    <div class="card-modern-header">Title</div>
    <div class="card-modern-body">Content</div>
</div>
```

### Button Migration
Old: `<button class="btn btn-primary">Click</button>`
New: `<button class="btn-primary-modern">Click</button>`

### Form Migration
Old: `<input type="text" class="form-control">`
New: `<input type="text" class="input-modern">`

## Support & Resources

### Tailwind CSS Documentation
https://tailwindcss.com/docs

### Color Palette Tool
Use the Tailwind color reference to find exact color codes.

### Component Library
All components are documented in this guide with examples.

## Version History

### Version 1.0.0 (December 2025)
- Initial modern design system implementation
- Full RTL support
- Dark mode integration
- Comprehensive component library
- Accessibility enhancements
- Performance optimizations

---

For questions or contributions, contact the development team.

