<?php

namespace Core\Enum;

enum  TargetType: int
{
    case ALL = 1;
    case TARGET = 2;
    case ADMINS = 3;
    case NONE = 4;
}
