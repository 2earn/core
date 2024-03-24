<?php

namespace Core\Services;


use Core\Enum\BalanceOperationsEnum;
use Core\Interfaces\IBalanceOperationRepositoty;
use Core\Interfaces\IUserBalancesRepository;


class BalancesManager
{
    private IUserBalancesRepository $userBalancesRepository;
    private IBalanceOperationRepositoty $balanceOperationRepositoty;

    public function __construct(
        IUserBalancesRepository     $userBalancesRepository,
        IBalanceOperationRepositoty $balanceOperationRepositoty)
    {
        $this->userBalancesRepository = $userBalancesRepository;
        $this->balanceOperationRepositoty = $balanceOperationRepositoty;
    }

    public function getBalances($IdUser)
    {
        return $this->userBalancesRepository->getBalance($IdUser);
    }
    public function getCurrentBalance($IdUser)
    {
        return $this->userBalancesRepository->getCurrentBalance($IdUser);
    }
    public function getBalanceOperation(BalanceOperationsEnum $operation)
    {
       return $this->balanceOperationRepositoty->getBalanceOperationById($operation);
    }
    public function getAllBalanceOperation()
    {
        return $this->balanceOperationRepositoty->getBalanceOperation( );
    }

}
