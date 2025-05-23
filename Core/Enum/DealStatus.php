<?php

namespace Core\Enum;

enum DealStatus: int
{

    case New = 1;
    case Opened = 2;
    case Closed = 3;
    case Archived = 4;

}
