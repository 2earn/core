<?php
namespace Core\Interfaces;



use Core\Enum\AmoutEnum;
use Core\Enum\BalanceOperationsEnum;
use Core\Models\calculated_userbalances;

interface  IUserBalancesRepository {
    public  function getBalance($idUser):calculated_userbalances ;
    public  function getCurrentBalance($idUser):calculated_userbalances ;
    public  function inserUserBalancestGetId($ref, BalanceOperationsEnum $operation,$date,$idSource,$iduserupline,$amount,$value);
    public function getSoldeByAmount($idUser,$idamount) ;
}
