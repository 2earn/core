<?php

namespace App\Enums;

enum OrderEnum: int
{
    case New = 1;
    case Ready = 2;
    case Simulated = 3;
    case Paid = 4;
    case Failed = 5;
    case Dispatched = 6;
}

