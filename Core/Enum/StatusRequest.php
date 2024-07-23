<?php

namespace Core\Enum;

enum StatusRequest: int
{
    case Registred = -2;
    case OptValidated = 0;
    case InProgressNational = 1;
    case InProgressInternational = 5;
    case ValidNational = 2;
    case Rejected = 3;
    case ValidInternational = 4;
}
