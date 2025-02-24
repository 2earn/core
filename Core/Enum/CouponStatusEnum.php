<?php

namespace Core\Enum;

enum  CouponStatusEnum: int
{
    case available = 0;
    case sold = 1;
    case used = 2;
    case expired = 3;

}
