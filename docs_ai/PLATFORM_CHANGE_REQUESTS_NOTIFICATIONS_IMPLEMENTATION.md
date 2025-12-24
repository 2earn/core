# Platform Change Request Notifications Implementation

## Summary
Implemented notifications for platform change requests when they are approved or rejected. The `requested_by` user will now receive notifications in their notification panel showing which fields were changed.

## Changes Made

### 1. Created Notification Classes

#### `app/Notifications/PlatformChangeRequestApproved.php`
- Sends notification when a platform change request is approved
- Includes platform name and the array of changes made
- Links to the platforms index page

#### `app/Notifications/PlatformChangeRequestRejected.php`
- Sends notification when a platform change request is rejected
- Includes platform name, changes requested, and rejection reason
- Links to the platforms index page

### 2. Updated Livewire Component

#### `app/Livewire/PlatformChangeRequests.php`
- Added notification dispatch in `approveRequest()` method
- Added notification dispatch in `rejectRequest()` method
- Notifications are sent to the user who created the change request (`requested_by`)

### 3. Created Notification Blade Templates

#### `resources/views/notifications/platform_change_request_approved.blade.php`
- Success notification with green styling
- Displays platform name
- Shows a list of changed fields (without values for security/privacy)
- Shows "View platform details" action button
- Includes mark as read functionality

#### `resources/views/notifications/platform_change_request_rejected.blade.php`
- Danger notification with red styling
- Displays platform name
- Shows a list of requested changes
- Shows rejection reason in a prominent alert box
- Shows "View platform details" action button
- Includes mark as read functionality

### 4. Updated Translation Files

Added translations for the new notifications in all language files:

#### English (`resources/lang/en/notifications.php`)
```php
'settings' => [
    'platform_change_request_approved' => 'Platform change request approved',
    'platform_change_request_rejected' => 'Platform change request rejected',
],
'platform_change_request_approved' => [
    'body' => 'Your platform change request for ":platform_name" has been approved',
    'action' => 'View platform details',
],
'platform_change_request_rejected' => [
    'body' => 'Your platform change request for ":platform_name" has been rejected',
    'action' => 'View platform details',
],
```

#### Arabic (`resources/lang/ar/notifications.php`)
```php
'settings' => [
    'platform_change_request_approved' => 'تمت الموافقة على طلب تغيير المنصة',
    'platform_change_request_rejected' => 'تم رفض طلب تغيير المنصة',
],
'platform_change_request_approved' => [
    'body' => 'تمت الموافقة على طلب تغيير المنصة ":platform_name" بنجاح',
    'action' => 'عرض تفاصيل المنصة',
],
'platform_change_request_rejected' => [
    'body' => 'تم رفض طلب تغيير المنصة ":platform_name"',
    'action' => 'عرض تفاصيل المنصة',
],
```

#### French (`resources/lang/fr/notifications.php`)
```php
'settings' => [
    'platform_change_request_approved' => 'Demande de modification de plateforme approuvée',
    'platform_change_request_rejected' => 'Demande de modification de plateforme rejetée',
],
'platform_change_request_approved' => [
    'body' => 'Votre demande de modification pour la plateforme ":platform_name" a été approuvée',
    'action' => 'Voir les détails de la plateforme',
],
'platform_change_request_rejected' => [
    'body' => 'Votre demande de modification pour la plateforme ":platform_name" a été rejetée',
    'action' => 'Voir les détails de la plateforme',
],
```

#### German (`resources/lang/de/notifications.php`)
```php
'settings' => [
    'platform_change_request_approved' => 'Plattformänderungsanfrage genehmigt',
    'platform_change_request_rejected' => 'Plattformänderungsanfrage abgelehnt',
],
'platform_change_request_approved' => [
    'body' => 'Ihre Plattformänderungsanfrage für ":platform_name" wurde genehmigt',
    'action' => 'Plattformdetails ansehen',
],
'platform_change_request_rejected' => [
    'body' => 'Ihre Plattformänderungsanfrage für ":platform_name" wurde abgelehnt',
    'action' => 'Plattformdetails ansehen',
],
```

#### Spanish (`resources/lang/sp/notifications.php`)
```php
'settings' => [
    'platform_change_request_approved' => 'Solicitud de modificación de plataforma aprobada',
    'platform_change_request_rejected' => 'Solicitud de modificación de plataforma rechazada',
],
'platform_change_request_approved' => [
    'body' => 'Su solicitud de modificación para la plataforma ":platform_name" ha sido aprobada',
    'action' => 'Ver detalles de la plataforma',
],
'platform_change_request_rejected' => [
    'body' => 'Su solicitud de modificación para la plataforma ":platform_name" ha sido rechazada',
    'action' => 'Ver detalles de la plataforma',
],
```

#### Turkish (`resources/lang/tr/notifications.php`)
```php
'settings' => [
    'platform_change_request_approved' => 'Platform değişiklik talebi onaylandı',
    'platform_change_request_rejected' => 'Platform değişiklik talebi reddedildi',
],
'platform_change_request_approved' => [
    'body' => '":platform_name" platformu için değişiklik talebiniz onaylandı',
    'action' => 'Platform detaylarını görün',
],
'platform_change_request_rejected' => [
    'body' => '":platform_name" platformu için değişiklik talebiniz reddedildi',
    'action' => 'Platform detaylarını görün',
],
```

#### Russian (`resources/lang/ru/notifications.php`)
```php
'settings' => [
    'platform_change_request_approved' => 'Запрос на изменение платформы одобрен',
    'platform_change_request_rejected' => 'Запрос на изменение платформы отклонён',
],
'platform_change_request_approved' => [
    'body' => 'Ваш запрос на изменение платформы ":platform_name" одобрен',
    'action' => 'Посмотреть детали платформы',
],
'platform_change_request_rejected' => [
    'body' => 'Ваш запрос на изменение платформы ":platform_name" отклонён',
    'action' => 'Посмотреть детали платформы',
],
```

## Features

### Approved Notification
- **Visual Design**: Green success theme with check circle icon
- **Content**: Shows platform name and list of changed fields
- **Changed Fields**: Displays field names (e.g., "name", "description", "link") without showing actual values for security
- **Action**: Links to platform details page
- **Badge**: Shows "New" badge for unread notifications

### Rejected Notification
- **Visual Design**: Red danger theme with times circle icon
- **Content**: Shows platform name and list of requested changes
- **Changed Fields**: Displays field names that were requested to be changed
- **Rejection Reason**: Displayed in a prominent alert box
- **Action**: Links to platform details page
- **Badge**: Shows "New" badge for unread notifications

## Database Structure

The notifications are stored in the `notifications` table with the following data structure:

```json
{
    "idUser": 123,
    "url": "http://example.com/en/platforms",
    "message_params": {
        "platform_name": "Example Platform",
        "changes": {
            "name": "New Name",
            "description": "New Description",
            "link": "https://new-link.com"
        },
        "rejection_reason": "Reason text (only for rejected)" 
    }
}
```

## User Flow

1. **Admin approves a platform change request**
   - User receives a green success notification
   - Notification shows the platform name and which fields were changed
   - User can click to view platform details and see the approved changes

2. **Admin rejects a platform change request**
   - User receives a red danger notification
   - Notification shows the platform name, which fields were requested to change, and rejection reason
   - User can click to view platform details and potentially resubmit with modifications

## Testing

To test the notifications:

1. Create a platform change request as a regular user (edit platform details)
2. Log in as an admin and approve/reject the request
3. Log back in as the original user
4. Check the notification panel for the new notification
5. Verify the notification content shows the correct platform and changed fields
6. Verify the rejection reason is displayed for rejected requests
7. Verify links work correctly

## Notes

- Notifications are sent using Laravel's built-in notification system
- Database channel is used (notifications appear in the notification panel)
- Notifications include localized content based on the user's locale
- The notification blade templates follow the same design pattern as existing notifications
- All 7 supported languages have been updated with translations
- The changes array shows only field names, not actual values, for security and brevity
- The changes can include any platform fields: name, description, link, enabled, type, show_profile, image_link, owner_id, marketing_manager_id, financial_manager_id, business_sector_id

## Related Features

This notification system complements the existing platform management features:
- Platform Validation Requests
- Platform Type Change Requests
- Platform Change Requests (this implementation)

All three now have complete notification support for both approval and rejection scenarios.

## Date
December 23, 2025

