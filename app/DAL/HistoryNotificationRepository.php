<?php

namespace App\DAL;

use App\Models\User;
use Core\Interfaces\IHistoryNotificationRepository;
use Illuminate\Support\Facades\DB;

class  HistoryNotificationRepository implements IHistoryNotificationRepository
{
    private string $req;


    public function getAllHistory()
    {
        // TODO: Implement getAllHistory() method.
    }

    public function getHistoryForModerateur()
    {

        return DB::select(getSqlFromPath('history_notification_repository'));
    }

    public function getHistoryByRole()
    {
        switch (strtoupper(auth()->user()->getRoleNames()->first())) {
            case  User::SUPER_ADMIN_ROLE_NAME :
                return DB::select(getSqlFromPath('history_notification_repository_admin'));
                break;
            case strtoupper("Admin"):
                return DB::select(getSqlFromPath('history_notification_repository_admin'));
                break;
            case strtoupper("Moderateur") :
                return DB::select(getSqlFromPath('history_notification_repository_moderateur'));
                break;
            case strtoupper("user") :
                return DB::select(getSqlFromPath('history_notification_repository_user') . auth()->user()->idUser);
                break;
        }
    }

}
