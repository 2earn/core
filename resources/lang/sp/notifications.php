<?php
return [
    'settings' => [
        'change_pwd_sms' => 'Contraseña cambiada por SMS',
        'validate_phone_email' => 'Validación del teléfono por correo electrónico',
        'delivery_sms' => 'Notificación de entrega por SMS',
        'share_purchase' => 'Compra de acciones',
        'cash_to_bfs' => 'Efectivo convertido a BFS',
    ],
    'delivery_sms' => [
        'body' => 'Hola :name, ¡tu paquete está en camino!',
        'action' => 'Rastrear entrega',
    ],
    'share_purchase' => [
        'body' => '¡Compra de acciones confirmada!',
        'action' => 'Rastrear acciones',
    ],
    'cash_to_bfs' => [
        'body' => 'Efectivo convertido a BFS con éxito',
        'action' => 'Rastrear BFS',
    ],
];
