<?php

namespace Core\Enum;

enum DealStatus: int
{

    case New = 1;
    case Open = 2;
    case Closed = 3;
    case Archived = 4;

}
