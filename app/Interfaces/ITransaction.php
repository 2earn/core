<?php
namespace App\Interfaces;

interface  ITransaction {
    public function beginTransaction();
    public function commit();
    public function rollback();
}
