# Platform Role Assignment Notifications Implementation

## Summary
Implemented notifications for platform role assignments when they are approved or rejected. The `user_id` user (the person being assigned the role) will now receive notifications in their notification panel.

## Changes Made

### 1. Created Notification Classes

#### `app/Notifications/PlatformRoleAssignmentApproved.php`
- Sends notification when a platform role assignment is approved
- Includes platform name and role type (owner, marketing_manager, financial_manager)
- Links to the platforms index page

#### `app/Notifications/PlatformRoleAssignmentRejected.php`
- Sends notification when a platform role assignment is rejected
- Includes platform name, role type, and rejection reason
- Links to the platforms index page

### 2. Updated Livewire Component

#### `app/Livewire/AssignPlatformRolesIndex.php`
- Added notification dispatch in `approve()` method
- Updated notification dispatch in `reject()` method (rejection notification was already partially implemented)
- Notifications are sent to the user who is being assigned the role (`user_id`)

### 3. Created Notification Blade Templates

#### `resources/views/notifications/platform_role_assignment_approved.blade.php`
- Success notification with green styling
- User check icon
- Displays platform name and role (formatted with spaces instead of underscores)
- Shows "View platform details" action button
- Includes mark as read functionality

#### `resources/views/notifications/platform_role_assignment_rejected.blade.php`
- Danger notification with red styling
- User times icon
- Displays platform name and role
- Shows rejection reason in a prominent alert box
- Shows "View details" action button
- Includes mark as read functionality

### 4. Updated Translation Files

Added translations for the new notifications in all language files:

#### English (`resources/lang/en/notifications.php`)
```php
'settings' => [
    'platform_role_assignment_approved' => 'Platform role assignment approved',
    'platform_role_assignment_rejected' => 'Platform role assignment rejected',
],
'platform_role_assignment_approved' => [
    'body' => 'You have been assigned as :role for platform ":platform_name"',
    'action' => 'View platform details',
],
'platform_role_assignment_rejected' => [
    'body' => 'Your role assignment as :role for platform ":platform_name" has been rejected',
    'action' => 'View details',
],
```

#### Arabic (`resources/lang/ar/notifications.php`)
```php
'settings' => [
    'platform_role_assignment_approved' => 'تمت الموافقة على تعيين دور المنصة',
    'platform_role_assignment_rejected' => 'تم رفض تعيين دور المنصة',
],
'platform_role_assignment_approved' => [
    'body' => 'تم تعيينك كـ :role للمنصة ":platform_name"',
    'action' => 'عرض تفاصيل المنصة',
],
'platform_role_assignment_rejected' => [
    'body' => 'تم رفض تعيينك كـ :role للمنصة ":platform_name"',
    'action' => 'عرض التفاصيل',
],
```

#### French (`resources/lang/fr/notifications.php`)
```php
'settings' => [
    'platform_role_assignment_approved' => 'Attribution de rôle de plateforme approuvée',
    'platform_role_assignment_rejected' => 'Attribution de rôle de plateforme rejetée',
],
'platform_role_assignment_approved' => [
    'body' => 'Vous avez été assigné comme :role pour la plateforme ":platform_name"',
    'action' => 'Voir les détails de la plateforme',
],
'platform_role_assignment_rejected' => [
    'body' => 'Votre attribution de rôle comme :role pour la plateforme ":platform_name" a été rejetée',
    'action' => 'Voir les détails',
],
```

#### German (`resources/lang/de/notifications.php`)
```php
'settings' => [
    'platform_role_assignment_approved' => 'Plattformrollenzuweisung genehmigt',
    'platform_role_assignment_rejected' => 'Plattformrollenzuweisung abgelehnt',
],
'platform_role_assignment_approved' => [
    'body' => 'Sie wurden als :role für die Plattform ":platform_name" zugewiesen',
    'action' => 'Plattformdetails ansehen',
],
'platform_role_assignment_rejected' => [
    'body' => 'Ihre Rollenzuweisung als :role für die Plattform ":platform_name" wurde abgelehnt',
    'action' => 'Details ansehen',
],
```

#### Spanish (`resources/lang/sp/notifications.php`)
```php
'settings' => [
    'platform_role_assignment_approved' => 'Asignación de rol de plataforma aprobada',
    'platform_role_assignment_rejected' => 'Asignación de rol de plataforma rechazada',
],
'platform_role_assignment_approved' => [
    'body' => 'Ha sido asignado como :role para la plataforma ":platform_name"',
    'action' => 'Ver detalles de la plataforma',
],
'platform_role_assignment_rejected' => [
    'body' => 'Su asignación de rol como :role para la plataforma ":platform_name" ha sido rechazada',
    'action' => 'Ver detalles',
],
```

#### Turkish (`resources/lang/tr/notifications.php`)
```php
'settings' => [
    'platform_role_assignment_approved' => 'Platform rol ataması onaylandı',
    'platform_role_assignment_rejected' => 'Platform rol ataması reddedildi',
],
'platform_role_assignment_approved' => [
    'body' => '":platform_name" platformu için :role olarak atandınız',
    'action' => 'Platform detaylarını görün',
],
'platform_role_assignment_rejected' => [
    'body' => '":platform_name" platformu için :role olarak rol atamanız reddedildi',
    'action' => 'Detayları görün',
],
```

#### Russian (`resources/lang/ru/notifications.php`)
```php
'settings' => [
    'platform_role_assignment_approved' => 'Назначение роли платформы одобрено',
    'platform_role_assignment_rejected' => 'Назначение роли платформы отклонено',
],
'platform_role_assignment_approved' => [
    'body' => 'Вы назначены как :role для платформы ":platform_name"',
    'action' => 'Посмотреть детали платформы',
],
'platform_role_assignment_rejected' => [
    'body' => 'Ваше назначение на роль :role для платформы ":platform_name" отклонено',
    'action' => 'Посмотреть детали',
],
```

## Features

### Approved Notification
- **Visual Design**: Green success theme with user check icon
- **Content**: Shows platform name and role being assigned
- **Role Display**: Automatically formats role names (e.g., "marketing_manager" becomes "Marketing Manager")
- **Action**: Links to platform details page
- **Badge**: Shows "New" badge for unread notifications

### Rejected Notification
- **Visual Design**: Red danger theme with user times icon
- **Content**: Shows platform name and role that was rejected
- **Role Display**: Automatically formats role names
- **Rejection Reason**: Displayed in a prominent alert box
- **Action**: Links to platform details page
- **Badge**: Shows "New" badge for unread notifications

## Roles Supported

The system supports three platform roles:
1. **Owner** (`owner`) - Platform owner with full control
2. **Marketing Manager** (`marketing_manager`) - Manages marketing activities
3. **Financial Manager** (`financial_manager`) - Manages financial operations

## Database Structure

The notifications are stored in the `notifications` table with the following data structure:

```json
{
    "idUser": 123,
    "url": "http://example.com/en/platforms",
    "message_params": {
        "platform_name": "Example Platform",
        "role": "marketing_manager",
        "rejection_reason": "Reason text (only for rejected)" 
    }
}
```

## User Flow

1. **Admin approves a platform role assignment**
   - User receives a green success notification
   - Notification shows which platform they've been assigned to and what role
   - User can click to view platform details and start working in their new role

2. **Admin rejects a platform role assignment**
   - User receives a red danger notification
   - Notification shows which platform and role was rejected, plus the reason why
   - User can click to view details and understand why the assignment was rejected

## Testing

To test the notifications:

1. As an admin, create a role assignment for a user (assign them as owner, marketing manager, or financial manager)
2. Approve or reject the assignment
3. Log in as the assigned user
4. Check the notification panel for the new notification
5. Verify the notification content shows the correct platform, role, and (for rejections) reason
6. Verify links work correctly

## Notes

- Notifications are sent using Laravel's built-in notification system
- Database channel is used (notifications appear in the notification panel)
- Notifications include localized content based on the user's locale
- The notification blade templates follow the same design pattern as existing notifications
- All 7 supported languages have been updated with translations
- Role names are automatically formatted in the blade templates (underscores replaced with spaces, capitalized)
- The rejection notification was already partially implemented, this update adds the approval notification
- Both approval and rejection now properly notify the `user_id` (the person being assigned)

## Related Features

This notification system is part of the platform role management system which includes:
- Platform Validation Requests
- Platform Type Change Requests
- Platform Change Requests
- Platform Role Assignments (this implementation)

All platform-related workflows now have complete notification support for both approval and rejection scenarios.

## Date
December 23, 2025

