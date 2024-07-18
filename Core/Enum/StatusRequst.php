<?php

namespace Core\Enum;

enum StatusRequst: int
{
    case EnCours = 1;
    case ValidNational = 2;
    case Rejected = 3;
    case ValidInternational = 4;
}
