<?php

namespace App\Services;


use App\Enums\BalanceOperationsEnum;
use App\Interfaces\IBalanceOperationRepositoty;
use App\Interfaces\IUserBalancesRepository;


class BalancesManager
{
    public function __construct(private IUserBalancesRepository $userBalancesRepository, private IBalanceOperationRepositoty $balanceOperationRepositoty)
    {

    }

    public function getBalances($IdUser, $decimals = 2)
    {
        return $this->userBalancesRepository->getBalance($IdUser, $decimals);
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
        return $this->balanceOperationRepositoty->getBalanceOperation();
    }

}
