<?php
namespace App\Enums;

enum BalanceEnum: int
{
    case CASH = 1;
    case BFS = 2;
    case DB = 3;
    case TREE = 4;
    case SMS = 5;
    case SHARE = 6;
    case CHANCE = 7;
}

