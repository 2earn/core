<?php

namespace App\Enums;

enum CommissionTypeEnum: int
{
    case OUT = 0;
    case IN = 1;
    case RECOVERED = 2;
}

