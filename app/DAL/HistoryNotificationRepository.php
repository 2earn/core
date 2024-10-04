<?php

namespace App\DAL;

use App\Models\User;
use Core\Enum\RoleEnum;
use Core\Interfaces\IHistoryNotificationRepository;
use Illuminate\Support\Facades\DB;

class  HistoryNotificationRepository implements IHistoryNotificationRepository
{
    private string $req = 'select   history.ref reference ,  IFNULL(usend.name , "System")   send  , users.name   receiver ,
ifnull(title,"")  action  ,history.date date , history.type ,  ifnull( history.reponce,"") responce from history
 inner join users on history.id_reciver = users.idUser
 left join users usend on history.id_send = users.idUser
 left join action_history on history.id_action = action_history.id
 where id_reciver !="11111111 "
 union all
 select
history.ref reference , IFNULL( users.name , " ")   send  , "System"  receiver ,
ifnull(title,"")  action  ,history.date date , history.type ,  ifnull( history.reponce,"") responce from history
 inner join users on history.id_send = users.idUser
  left join action_history on history.id_action = action_history.id
 where id_reciver ="11111111" ;
 ';


//    public function getNotificationByIdUser($id)
//    {
//        return DB::table('notif_user_settings')->where('idUser',$id)->first();
//    }
    public function getAllHistory()
    {
        // TODO: Implement getAllHistory() method.
    }

    public function getHistoryForModerateur()
    {
//        dd($this->req) ;
        return DB::select($this->req);
    }
    public function getHistoryByRole()
    {
        $userRole = auth()->user()->getRoleNames()->first() ;

       $idUser = auth()->user()->idUser;
        $req = "";
        switch ($userRole) {
            case  User::SUPER_ADMIN_ROLE_NAME :
            case "Admin":
                $req = 'select history.ref reference ,  IFNULL(usend.name , "System")   send  , users.name   receiver ,
ifnull(title,"")  action  ,history.date date , history.type ,  ifnull( history.reponce,"") responce from history
 inner join users on history.id_reciver = users.idUser
 left join users usend on history.id_send = users.idUser
 left join action_history on history.id_action = action_history.id';
                break;
            case "Moderateur" :
                  $req = 'select   history.ref reference ,  IFNULL(usend.name , "System")   send  , users.name   receiver ,
ifnull(title,"")  action  ,history.date date , history.type ,  ifnull( history.reponce,"") responce from history
 inner join users on history.id_reciver = users.idUser
 left join users usend on history.id_send = users.idUser
 left join action_history on history.id_action = action_history.id
 where id_reciver !="11111111 "
 union all
 select
history.ref reference , IFNULL( users.name , " ")   send  , "System"  receiver ,
ifnull(title,"")  action  ,history.date date , history.type ,  ifnull( history.reponce,"") responce from history
 inner join users on history.id_send = users.idUser
  left join action_history on history.id_action = action_history.id
 where id_reciver ="11111111" ;
 ';
                break;
            case "user" :
                $req = 'select history.ref reference ,  IFNULL(usend.name , "System")   send  , users.name   receiver ,
ifnull(title,"")  action  ,history.date date , history.type ,  ifnull( history.reponce,"") responce from history
 inner join users on history.id_reciver = users.idUser
 left join users usend on history.id_send = users.idUser
 left join action_history on history.id_action = action_history.id
 where id_reciver = '.$idUser;
                break;
        }
        return DB::select($req);
    }

}
