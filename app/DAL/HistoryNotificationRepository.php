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
        $userRole = auth()->user()->getRoleNames()->first();

        $idUser = auth()->user()->idUser;
        $req = "";
        switch ($userRole) {
            case  User::SUPER_ADMIN_ROLE_NAME :
            case "Admin":
                $req = getSqlFromPath('history_notification_repository_admin');
                break;
            case "Moderateur" :
                $req = getSqlFromPath('history_notification_repository_moderateur');
                break;
            case "user" :
                $req = getSqlFromPath('history_notification_repository_user') . $idUser;
                break;
        }
        return DB::select($req);
    }

}
