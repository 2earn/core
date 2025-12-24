<?php
return [
    'settings' => [
        'change_pwd_sms' => 'Password changed via SMS',
        'validate_phone_email' => 'Phone validation via Email',
        'delivery_sms' => 'Delivery notification via SMS',
        'share_purchase' => 'Share purchase',
        'cash_to_bfs' => 'Cash to bfs',
        'order_completed' => 'Order completed',
        'survey_participation' => 'Survey participation',
        'financial_request_sent' => 'Financial request sent',
        'partnership_request_sent' => 'Partnership request sent',
        'partnership_request_validated' => 'Partnership request validated',
        'platform_validation_request_approved' => 'Platform validation request approved',
        'platform_validation_request_rejected' => 'Platform validation request rejected',
        'platform_type_change_request_approved' => 'Platform type change request approved',
        'platform_type_change_request_rejected' => 'Platform type change request rejected',
        'platform_change_request_approved' => 'Platform change request approved',
        'platform_change_request_rejected' => 'Platform change request rejected',
        'platform_role_assignment_approved' => 'Platform role assignment approved',
        'platform_role_assignment_rejected' => 'Platform role assignment rejected',
        'deal_validation_request_approved' => 'Deal validation request approved',
        'deal_validation_request_rejected' => 'Deal validation request rejected',
        'deal_change_request_approved' => 'Deal change request approved',
        'deal_change_request_rejected' => 'Deal change request rejected',
        'partner_payment_validated' => 'Partner payment validated',
        'partner_payment_rejected' => 'Partner payment rejected',
    ],
    'delivery_sms' => [
        'body' => 'Hello :name, your package is on the way!',
        'action' => 'Track your delivery',
    ],
    'share_purchase' => [
        'body' => 'Share purchasing is confirmed!',
        'action' => 'Track your shares',
    ],
    'cash_to_bfs' => [
        'body' => 'Cash to bfs converted',
        'action' => 'Track your BFS',
    ],
    'order_completed' => [
        'body' => 'Order completed successfully',
        'action' => 'See your Order',
    ],
    'survey_participation' => [
        'body' => 'Survey participation completed successfully',
        'action' => 'See the survey',
    ],
    'financial_request_sent' => [
        'body' => 'Financial request sent successfully',
        'action' => 'See financial request',
    ],
    'partnership_request_sent' => [
        'body' => 'Your partnership request has been sent successfully',
        'action' => 'View partnership request',
    ],
    'partnership_request_validated' => [
        'body' => 'Your partnership request has been validated successfully',
        'action' => 'View partnership details',
    ],
    'platform_validation_request_approved' => [
        'body' => 'Your platform validation request for ":platform_name" has been approved successfully',
        'action' => 'View platform details',
    ],
    'platform_validation_request_rejected' => [
        'body' => 'Your platform validation request for ":platform_name" has been rejected',
        'action' => 'View platform details',
    ],
    'platform_type_change_request_approved' => [
        'body' => 'Your platform type change request for ":platform_name" from :old_type to :new_type has been approved',
        'action' => 'View platform details',
    ],
    'platform_type_change_request_rejected' => [
        'body' => 'Your platform type change request for ":platform_name" from :old_type to :new_type has been rejected',
        'action' => 'View platform details',
    ],
    'platform_change_request_approved' => [
        'body' => 'Your platform change request for ":platform_name" has been approved',
        'action' => 'View platform details',
    ],
    'platform_change_request_rejected' => [
        'body' => 'Your platform change request for ":platform_name" has been rejected',
        'action' => 'View platform details',
    ],
    'platform_role_assignment_approved' => [
        'body' => 'You have been assigned as :role for platform ":platform_name"',
        'action' => 'View platform details',
    ],
    'platform_role_assignment_rejected' => [
        'body' => 'Your role assignment as :role for platform ":platform_name" has been rejected',
        'action' => 'View details',
    ],
    'deal_validation_request_approved' => [
        'body' => 'Your deal validation request for ":deal_name" has been approved successfully',
        'action' => 'View deal details',
    ],
    'deal_validation_request_rejected' => [
        'body' => 'Your deal validation request for ":deal_name" has been rejected',
        'action' => 'View deal details',
    ],
    'partner_payment_validated' => [
        'body' => 'Your partner payment of :amount has been validated successfully',
        'action' => 'View payment details',
    ],
    'partner_payment_rejected' => [
        'body' => 'Your partner payment of :amount has been rejected. Reason: :reason',
        'action' => 'View payment details',
    ],
];
