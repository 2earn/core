<?php
namespace Core\Interfaces;

interface  IHistoryNotificationRepository {
    public function getAllHistory();
    public function getHistoryForModerateur();
    public function getHistoryByRole();
}
