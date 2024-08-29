<?php

namespace Core\Enum;

enum StatusSurvey: int
{
    case NEW = 1;
    case OPEN = 2;
    case CLOSED = 3;
    case ARCHIVED = 4;
}
