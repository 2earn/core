<?php

namespace Core\Enum;

enum CommittedInvestorRequestStatus: int
{

    case InProgress = 1;
    case Validated = 2;
    case Rejected = 3;

}
