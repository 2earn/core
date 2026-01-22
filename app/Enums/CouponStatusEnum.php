<?php

namespace App\Enums;

enum CouponStatusEnum: string
{
    case    available = "0";
    case    reserved = "1";
    case    purchased = "2";
    case    consumed = "3";
}



