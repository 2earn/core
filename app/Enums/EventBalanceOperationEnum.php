<?php
namespace App\Enums;

enum EventBalanceOperationEnum : int
{
    case Signup = 1;
    case ExchangeCashToBFS = 2;
    case ExchangeBFSToSMS = 3;
    case SendSMS = 4;
    case SendToPublicFromCash = 5;
    case SendToPublicFromBFS = 6;
}

