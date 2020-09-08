<?php

use App\Enums\OrderStatus;

return [

    OrderStatus::class => [
        OrderStatus::AddNew => 'Đơn Hàng mới',
        OrderStatus::Confirmed => 'Đã xác nhận ',
        OrderStatus::Shipping => 'Đang giao hàng',
        OrderStatus::Done => 'Hoàn thành',
        OrderStatus::Cancel => 'Huỷ',
    ],

];