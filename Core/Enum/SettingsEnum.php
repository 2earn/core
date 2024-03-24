<?php
namespace Core\Enum ;

Enum SettingsEnum:int
{
    case discount_By_registering=1;
    case discount__By_registering_5_people=2;
    case discount__By_visiting_the_products=3;
    case discount_Have_someone_attend_a_presentation=4;
    case discount__Each_Share_leading_to_a_purchase=5;
    case token_By_registering=6;
    case token_By_registering_5_people=7;
    case token_By_visiting_the_products=8;
    case token_Have_someone_attend_a_presentation=9;
    case token_Each_Share_leading_to_a_purchase=10;
    case password_super_admin=12;
    case Prix_SMS=13;
    case NbrSmsPossible = 15 ;

}
