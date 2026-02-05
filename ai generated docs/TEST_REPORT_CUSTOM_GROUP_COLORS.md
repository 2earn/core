# Custom Group Badge Colors - Quick Reference

## Adding Your Own Group Badge Colors

This guide shows you how to add custom colors for any test group.

## Default Available Colors

These groups have pre-defined colors (see full list in main documentation):
- slow, fast, integration, unit, feature, api, database, vip, service, controller

## Adding a New Custom Color

### Example: Adding a "security" Group Badge

#### Step 1: Edit the CSS File
Open: `public/css/test-report.css`

#### Step 2: Add Your Style Before the Default Style
Find the "Default for unknown groups" comment and add your style BEFORE it:

```css
/* Your custom group */
.group-badge.group-security {
    background: linear-gradient(135deg, #dc3545 0%, #bd2130 100%);
    color: #fff;
}

/* Default for unknown groups */
.group-badge:not([class*="group-slow"])... {
    /* existing default style */
}
```

#### Step 3: Use in Your Tests
```php
/**
 * @group security
 * @group integration
 */
class AuthSecurityTest extends TestCase
{
    // tests...
}
```

#### Step 4: Regenerate Report
```bash
php artisan test:report
```

## Color Gradient Generator

Use these gradient combinations for professional-looking badges:

### Red/Danger Variants
```css
/* Dark Red */
background: linear-gradient(135deg, #dc3545 0%, #bd2130 100%);

/* Light Red */
background: linear-gradient(135deg, #ff6b6b 0%, #ff8e53 100%);

/* Pink */
background: linear-gradient(135deg, #e83e8c 0%, #d63384 100%);
```

### Blue Variants
```css
/* Primary Blue */
background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);

/* Cyan */
background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);

/* Dark Blue */
background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);
```

### Green Variants
```css
/* Success Green */
background: linear-gradient(135deg, #28a745 0%, #20c997 100%);

/* Teal */
background: linear-gradient(135deg, #20c997 0%, #17a689 100%);

/* Lime */
background: linear-gradient(135deg, #8bc34a 0%, #689f38 100%);
```

### Purple Variants
```css
/* Deep Purple */
background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%);

/* Blue-Purple */
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

/* Violet */
background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%);
```

### Orange/Yellow Variants
```css
/* Warning Orange */
background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);

/* Bright Orange */
background: linear-gradient(135deg, #fd7e14 0%, #e8590c 100%);

/* Gold */
background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
```

### Gray/Dark Variants
```css
/* Medium Gray */
background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);

/* Dark Gray */
background: linear-gradient(135deg, #343a40 0%, #23272b 100%);

/* Light Gray */
background: linear-gradient(135deg, #adb5bd 0%, #868e96 100%);
```

## Complete Custom Badge Template

```css
.group-badge.group-YOURGROUP {
    background: linear-gradient(135deg, #COLOR1 0%, #COLOR2 100%);
    color: #fff; /* or #333 for dark text */
    /* Optional extras: */
    font-weight: 700; /* for bold text */
    border: 2px solid #BORDERCOLOR; /* for border */
}
```

## Common Use Cases

### Security Tests
```css
.group-badge.group-security {
    background: linear-gradient(135deg, #dc3545 0%, #bd2130 100%);
    color: #fff;
}
```

### Performance Tests
```css
.group-badge.group-performance {
    background: linear-gradient(135deg, #fd7e14 0%, #e8590c 100%);
    color: #fff;
}
```

### Smoke Tests
```css
.group-badge.group-smoke {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    color: #fff;
}
```

### Critical Tests
```css
.group-badge.group-critical {
    background: linear-gradient(135deg, #ff0000 0%, #cc0000 100%);
    color: #fff;
    font-weight: 700;
    border: 2px solid #ffffff;
}
```

### Regression Tests
```css
.group-badge.group-regression {
    background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%);
    color: #fff;
}
```

### E2E Tests
```css
.group-badge.group-e2e {
    background: linear-gradient(135deg, #3f51b5 0%, #303f9f 100%);
    color: #fff;
}
```

### Browser Tests
```css
.group-badge.group-browser {
    background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%);
    color: #fff;
}
```

### External API Tests
```css
.group-badge.group-external {
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
    color: #fff;
}
```

## Text Color Guidelines

### White Text (`color: #fff`)
Use on dark backgrounds:
- All gradients with colors darker than 50% lightness
- Most colored backgrounds

### Dark Text (`color: #333`)
Use on light backgrounds:
- Gold/Yellow badges
- Light gray badges
- Pastel colors

### Example with Dark Text:
```css
.group-badge.group-light {
    background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
    color: #333; /* Dark text on light background */
    font-weight: 700; /* Make text stand out */
}
```

## Accessibility Tips

1. **Ensure Good Contrast**: Text should be readable on the background
   - Use white text on dark colors
   - Use dark text on light colors

2. **Test with Tools**: Use browser dev tools to check contrast ratio
   - Aim for at least 4.5:1 for normal text
   - 3:1 for large text

3. **Avoid Pure Colors**: Gradients are easier on the eyes than flat colors

4. **Consider Colorblind Users**: Use different shades, not just hues

## Badge Hover Effect

All badges automatically get this hover effect:
```css
.group-badge:hover {
    transform: translateY(-2px); /* Lifts up 2px */
    box-shadow: 0 4px 8px rgba(0,0,0,0.15); /* Adds shadow */
}
```

To customize for a specific group:
```css
.group-badge.group-YOURGROUP:hover {
    transform: translateY(-4px) scale(1.05); /* Bigger lift + scale */
    box-shadow: 0 8px 16px rgba(0,0,0,0.2); /* Bigger shadow */
}
```

## Multiple Badge Combinations

Badges automatically arrange side-by-side with proper spacing. No special CSS needed!

```php
/**
 * @group security
 * @group api
 * @group integration
 * @group critical
 */
class ComplexTest extends TestCase
{
    // Will show 4 badges in a row
}
```

## Location in Codebase

**CSS File**: `public/css/test-report.css`  
**Lines**: Around 260-360 (group badge section)  
**Insert Position**: Before the default style (around line 360)

## Testing Your Changes

1. **Edit CSS**: Add your custom style
2. **Save File**: No compilation needed (plain CSS)
3. **Regenerate Report**: `php artisan test:report --skip-tests`
4. **Open HTML**: `tests/reports/test-report.html`
5. **Inspect Badge**: Use browser dev tools to verify

## Tips & Tricks

### 1. Use Online Gradient Generators
- [CSS Gradient](https://cssgradient.io/)
- [Gradient Hunt](https://gradienthunt.com/)
- [uiGradients](https://uigradients.com/)

### 2. Match Your Brand Colors
```css
.group-badge.group-brand {
    background: linear-gradient(135deg, #YOUR_BRAND_COLOR1 0%, #YOUR_BRAND_COLOR2 100%);
    color: #fff;
}
```

### 3. Seasonal Themes
```css
/* Christmas */
.group-badge.group-christmas {
    background: linear-gradient(135deg, #d42426 0%, #165b33 100%);
    color: #fff;
}

/* Halloween */
.group-badge.group-halloween {
    background: linear-gradient(135deg, #ff6600 0%, #000000 100%);
    color: #fff;
}
```

### 4. Emoji in Group Names
```php
/**
 * @group 🔥hot
 * @group ⚡fast
 */
```
Note: Keep emoji usage minimal for compatibility

## Common Issues

### Badge Not Showing?
1. Check if `@group` annotation is in the class-level PHPDoc
2. Verify the test file path is readable
3. Regenerate the report after adding groups

### Color Not Applied?
1. Ensure CSS class name matches: `.group-badge.group-EXACTNAME`
2. Check CSS file is saved
3. Hard refresh browser (Ctrl+F5)
4. Verify CSS is loaded (check browser console)

### Text Hard to Read?
1. Increase contrast between text and background
2. Try white text on dark backgrounds
3. Add `font-weight: 700` for bolder text
4. Add `text-shadow` for better readability

## Example: Complete Custom Group Setup

```css
/* In public/css/test-report.css */

/* Payment Tests - Green gradient */
.group-badge.group-payment {
    background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
    color: #fff;
}

/* Auth Tests - Blue gradient */
.group-badge.group-auth {
    background: linear-gradient(135deg, #0984e3 0%, #6c5ce7 100%);
    color: #fff;
}

/* Critical Path - Red with border */
.group-badge.group-critical {
    background: linear-gradient(135deg, #d63031 0%, #ff7675 100%);
    color: #fff;
    font-weight: 700;
    border: 2px solid rgba(255,255,255,0.3);
}
```

```php
// In your test file

/**
 * @group payment
 * @group critical
 * @group api
 */
class PaymentGatewayTest extends TestCase
{
    // tests...
}
```

Result: 3 badges displayed with custom colors!

---

**Pro Tip**: Keep a consistent color scheme across your test suite for better visual organization!

**Last Updated**: February 5, 2026
