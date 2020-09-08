<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static AddNew()
 * @method static static Confirmed()
 * @method static static Shipping()
 * @method static static Done()
 * @method static static Cancel()
 */
final class OrderStatus extends Enum implements LocalizedEnum
{
    const AddNew    =   1;
    const Confirmed =   10;
    const Shipping  =   15;
    const Done      =   20;
    const Cancel    =   99;
}
