<?php

namespace Core\Enum;

enum StatusRequst: int
{
    case EnCours = 1;
    case Valid = 2;
    case Rejected = 3;
}
