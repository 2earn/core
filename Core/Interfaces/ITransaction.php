<?php
namespace Core\Interfaces;

interface  ITransaction {
    public function beginTransaction();
    public function commit();
    public function rollback();
}
