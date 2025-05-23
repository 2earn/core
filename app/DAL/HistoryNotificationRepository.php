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

        $historyNotSystem = DB::table('history')
            ->select(
                'history.ref as reference',
                DB::raw('IFNULL(usend.name, "System") as send'),
                'users.name as receiver',
                DB::raw('IFNULL(title, "") as action'),
                'history.date',
                'history.type',
                DB::raw('IFNULL(history.reponce, "") as responce')
            )
            ->join('users', 'history.id_reciver', '=', 'users.idUser')
            ->leftJoin('users as usend', 'history.id_send', '=', 'usend.idUser')
            ->leftJoin('action_history', 'history.id_action', '=', 'action_history.id')
            ->where('history.id_reciver', '!=', "11111111");

        $historySystem = DB::table('history')
            ->select(
                'history.ref as reference',
                DB::raw('IFNULL(users.name, " ") as send'),
                DB::raw('"System" as receiver'),
                DB::raw('IFNULL(title, "") as action'),
                'history.date',
                'history.type',
                DB::raw('IFNULL(history.reponce, "") as responce')
            )
            ->join('users', 'history.id_send', '=', 'users.idUser')
            ->leftJoin('action_history', 'history.id_action', '=', 'action_history.id')
            ->where('history.id_reciver', '=', "11111111");

        return $historyNotSystem->unionAll($historySystem)->get();
    }

    public function getHistoryNotificationModerator()
    {

        $historyNotSystem = DB::table('history')
            ->select(
                'history.ref as reference',
                DB::raw('IFNULL(usend.name, "System") as send'),
                'users.name as receiver',
                DB::raw('IFNULL(title, "") as action'),
                'history.date',
                'history.type',
                DB::raw('IFNULL(history.reponce, "") as responce')
            )
            ->join('users', 'history.id_reciver', '=', 'users.idUser')
            ->leftJoin('users as usend', 'history.id_send', '=', 'usend.idUser')
            ->leftJoin('action_history', 'history.id_action', '=', 'action_history.id')
            ->where('history.id_reciver', '!=', "11111111");

        $historySystem = DB::table('history')
            ->select(
                'history.ref as reference',
                DB::raw('IFNULL(users.name, " ") as send'),
                DB::raw('"System" as receiver'),
                DB::raw('IFNULL(title, "") as action'),
                'history.date',
                'history.type',
                DB::raw('IFNULL(history.reponce, "") as responce')
            )
            ->join('users', 'history.id_send', '=', 'users.idUser')
            ->leftJoin('action_history', 'history.id_action', '=', 'action_history.id')
            ->where('history.id_reciver', '=', "11111111");

        return $historyNotSystem->unionAll($historySystem)->get();

    }

    public function getHistoryNotificationAdmin()
    {
        return DB::table('history')
            ->select(
                'history.ref as reference',
                DB::raw('IFNULL(usend.name,                "System") as send'),
                'users.name as receiver',
                DB::raw('IFNULL(title, "") as action'),
                'history.date', 'history.type',
                DB::raw('IFNULL(history.reponce, "") as responce')
            )
            ->join('users', 'history.id_reciver', '=', 'users.idUser')
            ->leftJoin('users as usend', 'history.id_send', '=', 'usend.idUser')
            ->leftJoin('action_history', 'history.id_action', '=', 'action_history.id')
            ->get();
    }

    public function getHistoryNotificationUser($receiverId)
    {
        return DB::table('history')->select(
            'history.ref as reference',
            DB::raw('IFNULL(usend.name, "System") as send'),
            'users.name as receiver', DB::raw('IFNULL(title, "") as action'),
            'history.date', 'history.type', DB::raw('IFNULL(history.reponce, "") as responce')
        )
            ->join('users', 'history.id_reciver', '=', 'users.idUser')
            ->leftJoin('users as usend', 'history.id_send', '=', 'usend.idUser')
            ->leftJoin('action_history', 'history.id_action', '=', 'action_history.id')
            ->where('history.id_reciver', $receiverId)->get();
    }

    public function getHistoryByRole()
    {
        $result = null;
        switch (strtoupper(auth()->user()->getRoleNames()->first())) {
            case  User::SUPER_ADMIN_ROLE_NAME :
                $result = $this->getHistoryNotificationAdmin();
                break;
            case strtoupper("Admin"):
                $result = $this->getHistoryNotificationAdmin();
                break;
            case strtoupper("Moderateur"):
                $result = $this->getHistoryNotificationModerator();
                break;
            case strtoupper("user") :
                $result = $this->getHistoryNotificationUser(auth()->user()->idUser);
                break;
        }
        return $result;
    }

}
