<?php
namespace Core\Interfaces;



use Core\Enum\BalanceEnum;
use Core\Enum\BalanceOperationsEnum;
use Core\Models\calculated_userbalances;

interface  IUserBalancesRepository {
    public  function getBalance($idUser) ;
    public  function getCurrentBalance($idUser) ;
    public  function inserUserBalancestGetId($ref, BalanceOperationsEnum $operation,$date,$idSource,$iduserupline,$amount,$value);
    public function getSoldeByAmount($idUser,$idamount) ;
}
