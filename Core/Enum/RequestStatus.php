<?php

namespace Core\Enum;

enum RequestStatus: int
{

    case InProgress = 1;
    case Validated = 2;
    case Rejected = 3;

}
