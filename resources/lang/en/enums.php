<?php

use App\Enums\OrderStatus;

return [

    OrderStatus::class => [
        OrderStatus::AddNew => 'Add new',
        OrderStatus::Confirmed => 'Confirmed',
        OrderStatus::Shipping => 'Shipping',
        OrderStatus::Done => 'Completed',
        OrderStatus::Cancel => 'Cancel',
    ],

];