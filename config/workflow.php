<?php
use App\Enums\OrderStatus;

return [
    'straight'   => [
        'type'          => 'workflow',
        'marking_store' => [
            'type' => 'single_state',
            'arguments' => ['status']
        ],
        'supports'      => ['App\Models\Order'],
        'places'        => OrderStatus::toArray(),
        'transitions'   => [
            OrderStatus::getKey(OrderStatus::Confirmed) => [
                'from' => OrderStatus::AddNew,
                'to'   => OrderStatus::Confirmed,
            ],
            OrderStatus::getKey(OrderStatus::Shipping) => [
                'from' => OrderStatus::Confirmed,
                'to'   => OrderStatus::Shipping,
            ],
            OrderStatus::getKey(OrderStatus::Done) => [
                // 'from' => OrderStatus::Shipping,
                'from' => [OrderStatus::AddNew, OrderStatus::Confirmed, OrderStatus::Shipping],
                'to'   => OrderStatus::Done,
            ],
            OrderStatus::getKey(OrderStatus::Cancel) => [
                'from' => [OrderStatus::AddNew, OrderStatus::Confirmed, OrderStatus::Shipping],
                'to'   => OrderStatus::Cancel,
            ]
        ],
    ]
];
