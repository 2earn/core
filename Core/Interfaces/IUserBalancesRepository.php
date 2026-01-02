<?php
namespace Core\Interfaces;



interface  IUserBalancesRepository {
    public  function getBalance($idUser) ;
    public  function getCurrentBalance($idUser) ;
    public function getSoldeByAmount($idUser,$idamount) ;
}
