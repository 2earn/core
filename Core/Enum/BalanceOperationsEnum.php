<?php
namespace Core\Enum;
enum BalanceOperationsEnum: int
{
    case By_registering_TREE = 1;
    case By_registering_5_people_TREE = 2;
    case By_visiting_the_products_TREE = 3;
    case Have_someone_attend_a_presentation_TREE = 4;
    case Each_Share_leading_to_a_purchase_TREE = 5;
    case By_registering_DB = 6;
    case By_registering_5_people_DB = 7;
    case By_visiting_the_products_DB = 8;
    case Have_someone_attend_a_presentation_DB = 9;
    case Each_Share_leading_to_a_purchase_DB = 10;
    case block_BFS_BFS = 11;
    case From_CASH_Balance_BFS = 13;
    case From_Other_Users_BFS = 14;
    case From_LIVRAISON_BFS = 15;
    case CASH_TO_BFS_CB = 16;
    case DEBIT_DE_SOLDE_BFS = 17;
    case SI_CB = 18;
    case SI_BFS = 19;
    case SI_DB = 20;
    case SI_TREE = 21;
    case to_Other_Users_CB = 22;
    case CASH_BACK_BFS = 23;
    case PRC_BFS = 24;
    case CASH_BACK_CB = 25;
    case PRC_CB = 26;
    case to_Other_Users_BFS = 28;
    case Toearn_to_user_cc_BFS = 29;
    case EnvoyeSMS = 40;
    case deblock_BFS_BFS = 27;
    case to_Other_Users_public_CB = 31;
    case to_Other_Users_admin_CB = 32;
    case From_representative_BFS = 33;
    case From_public_User_BFS = 34;
    case to_Other_Users_public_BFS = 35;
    case to_Other_Users_admin_BFS = 36;
    case SI_SMS = 37;
    case BFS_TO_SMSn_BFS = 38;
    case From_BFS_Balance_SMS = 39;
    const Achat_SMS_SMS = 40;
}


