<?php

namespace Core\Enum;

enum  CouponStatusEnum: string
{
    case available = "0";
    case sold = "1";
    case used = "2";
    case expired = "3";

}
