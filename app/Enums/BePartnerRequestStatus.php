<?php

namespace App\Enums;

enum BePartnerRequestStatus: int
{
    case InProgress = 1;
    case Validated2earn = 2;
    case Validated = 3;
    case Rejected = 4;
}

