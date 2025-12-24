<?php

return [
    'settings' => [
        'change_pwd_sms' => 'تم تغيير كلمة المرور عبر الرسائل القصيرة',
        'validate_phone_email' => 'تأكيد الهاتف عبر البريد الإلكتروني',
        'delivery_sms' => 'إشعار التسليم عبر الرسائل القصيرة',
        'share_purchase' => 'شراء الأسهم',
        'cash_to_bfs' => 'تحويل النقد إلى BFS',
        'order_completed' => 'تم إكمال الطلب',
        'survey_participation' => 'المشاركة في الاستطلاع',
        'financial_request_sent' => 'تم إرسال الطلب المالي',
        'partnership_request_sent' => 'تم إرسال طلب الشراكة',
        'partnership_request_validated' => 'تم التحقق من طلب الشراكة',
        'platform_validation_request_approved' => 'تمت الموافقة على طلب التحقق من المنصة',
        'platform_validation_request_rejected' => 'تم رفض طلب التحقق من المنصة',
        'platform_type_change_request_approved' => 'تمت الموافقة على طلب تغيير نوع المنصة',
        'platform_type_change_request_rejected' => 'تم رفض طلب تغيير نوع المنصة',
        'platform_change_request_approved' => 'تمت الموافقة على طلب تغيير المنصة',
        'platform_change_request_rejected' => 'تم رفض طلب تغيير المنصة',
        'platform_role_assignment_approved' => 'تمت الموافقة على تعيين الدور',
        'platform_role_assignment_rejected' => 'تم رفض تعيين الدور',
        'deal_validation_request_approved' => 'تمت الموافقة على طلب التحقق من الصفقة',
        'deal_validation_request_rejected' => 'تم رفض طلب التحقق من الصفقة',
        'deal_change_request_approved' => 'تمت الموافقة على طلب تعديل الصفقة',
        'deal_change_request_rejected' => 'تم رفض طلب تعديل الصفقة',
        'partner_payment_validated' => 'تم التحقق من دفعة الشريك',
        'partner_payment_rejected' => 'تم رفض دفعة الشريك',
    ],
    'delivery_sms' => [
        'body' => 'مرحباً :name، طردك في الطريق!',
        'action' => 'تتبع التسليم',
    ],
    'share_purchase' => [
        'body' => 'تم تأكيد شراء الأسهم!',
        'action' => 'تتبع الأسهم',
    ],
    'cash_to_bfs' => [
        'body' => 'تم تحويل النقد إلى BFS',
        'action' => 'تتبع BFS',
    ],
    'order_completed' => [
        'body' => 'تم إكمال الطلب بنجاح',
        'action' => 'عرض الطلب',
    ],
    'survey_participation' => [
        'body' => 'تمت المشاركة في الاستطلاع بنجاح',
        'action' => 'عرض الاستطلاع',
    ],
    'financial_request_sent' => [
        'body' => 'تم إرسال الطلب المالي بنجاح',
        'action' => 'عرض الطلب المالي',
    ],
    'partnership_request_sent' => [
        'body' => 'تم إرسال طلب الشراكة الخاص بك بنجاح',
        'action' => 'عرض طلب الشراكة',
    ],
    'partnership_request_validated' => [
        'body' => 'تم التحقق من طلب الشراكة الخاص بك بنجاح',
        'action' => 'عرض تفاصيل الشراكة',
    ],
    'platform_validation_request_approved' => [
        'body' => 'تمت الموافقة على طلب التحقق من المنصة ":platform_name" بنجاح',
        'action' => 'عرض تفاصيل المنصة',
    ],
    'platform_validation_request_rejected' => [
        'body' => 'تم رفض طلب التحقق من المنصة ":platform_name"',
        'action' => 'عرض تفاصيل المنصة',
    ],
    'platform_type_change_request_approved' => [
        'body' => 'تمت الموافقة على طلب تغيير نوع المنصة ":platform_name" من :old_type إلى :new_type',
        'action' => 'عرض تفاصيل المنصة',
    ],
    'platform_type_change_request_rejected' => [
        'body' => 'تم رفض طلب تغيير نوع المنصة ":platform_name" من :old_type إلى :new_type',
        'action' => 'عرض تفاصيل المنصة',
    ],
    'platform_change_request_approved' => [
        'body' => 'تمت الموافقة على طلب تغيير المنصة ":platform_name" بنجاح',
        'action' => 'عرض تفاصيل المنصة',
    ],
    'platform_change_request_rejected' => [
        'body' => 'تم رفض طلب تغيير المنصة ":platform_name"',
        'action' => 'عرض تفاصيل المنصة',
    ],
    'platform_role_assignment_approved' => [
        'body' => 'تم تعيينك كـ :role للمنصة ":platform_name"',
        'action' => 'عرض تفاصيل المنصة',
    ],
    'platform_role_assignment_rejected' => [
        'body' => 'تم رفض تعيينك كـ :role للمنصة ":platform_name"',
        'action' => 'عرض التفاصيل',
    ],
    'deal_validation_request_approved' => [
        'body' => 'تمت الموافقة على طلب التحقق من الصفقة ":deal_name" بنجاح',
        'action' => 'عرض تفاصيل الصفقة',
    ],
    'deal_validation_request_rejected' => [
        'body' => 'تم رفض طلب التحقق من الصفقة ":deal_name"',
        'action' => 'عرض تفاصيل الصفقة',
    ],
    'deal_change_request_approved' => [
        'body' => 'تمت الموافقة على طلب تعديل الصفقة ":deal_name"',
        'action' => 'عرض تفاصيل الصفقة',
    ],
    'deal_change_request_rejected' => [
        'body' => 'تم رفض طلب تعديل الصفقة ":deal_name"',
        'action' => 'عرض تفاصيل الصفقة',
    ],
    'partner_payment_validated' => [
        'body' => 'تم التحقق من دفعتك بمبلغ :amount بنجاح',
        'action' => 'عرض تفاصيل الدفعة',
    ],
    'partner_payment_rejected' => [
        'body' => 'تم رفض دفعتك بمبلغ :amount. السبب: :reason',
        'action' => 'عرض تفاصيل الدفعة',
    ],
];
