<?php
namespace Core\Enum;
enum BalanceOperationsEnum: int
{
    case BY_REGISTERING_TREE = 1;
    case BY_REGISTERING_5_PEOPLE_TREE = 2;
    case BY_VISITING_THE_PRODUCTS_TREE = 3;
    case HAVE_SOMEONE_ATTEND_A_PRESENTATION_TREE = 4;
    case EACH_SHARE_LEADING_TO_A_PURCHASE_TREE = 5;
    case BY_REGISTERING_DB = 6;
    case BY_REGISTERING_5_PEOPLE_DB = 7;
    case BY_VISITING_THE_PRODUCTS_DB = 8;
    case HAVE_SOMEONE_ATTEND_A_PRESENTATION_DB = 9;
    case EACH_SHARE_LEADING_TO_A_PURCHASE_DB = 10;
    case BLOCK_BFS_BFS = 11;
    case From_CASH_Balance_BFS = 13;
    case FROM_OTHER_USERS_BFS = 14;
    case From_LIVRAISON_BFS = 15;
    case CASH_TO_BFS_CB = 16;
    case DEBIT_DE_SOLDE_BFS = 17;
    case SI_CB = 18;
    case SI_BFS = 19;
    case SI_DB = 20;
    case SI_TREE = 21;
    case TO_OTHER_USERS_CB = 22;
    case CASH_BACK_BFS = 23;
    case PRC_BFS = 24;
    case CASH_BACK_CB = 25;
    case PRC_CB = 26;
    case TO_OTHER_USERS_BFS = 28;
    case TOEARN_TO_USER_CC_BFS = 29;
    case ENVOYE_SMS = 40;
    case DEBLOCK_BFS_BFS = 27;
    case TO_OTHER_USERS_PUBLIC_CB = 31;
    case TO_OTHER_USERS_ADMIN_CB = 32;
    case FROM_REPRESENTATIVE_BFS = 33;
    case FROM_PUBLIC_USER_BFS = 34;
    case TO_OTHER_USERS_PUBLIC_BFS = 35;
    case TO_OTHER_USERS_ADMIN_BFS = 36;
    case SI_SMS = 37;
    case BFS_TO_SM_SN_BFS = 38;
    case FROM_BFS_BALANCE_SMS = 39;
    const Achat_SMS_SMS = 40;
    const ACHAT_SMS = 41;
    const CASH_TRANSFERT_O = 42;
    const CASH_TRANSFERT_I = 43;
    const SELLED_SHARES = 44;
    const BY_ACQUIRING_SHARES = 46;
    const FROM_BFS = 47;
    const SELL_SHARES = 48;
    const SPONSORSHIP_COMMISSION_CASH = 49;
    const SPONSORSHIP_COMMISSION_BFS = 50;
    const CASH_TOP_UP_WITH_CARD = 51;
    const SPONSORSHIP_COMMISSION_SHARE = 52;
    const PRICE_CHANGE = 53;
    const COMPLIMENTARY_BENEFITS_ON_PURCHASED_SHARES = 54;
    const VIP_BENEFITS_ON_PURCHASED_SHARES = 55;
    const INITIAL_CHANE = 56;

}


