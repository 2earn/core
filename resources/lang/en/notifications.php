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
];
