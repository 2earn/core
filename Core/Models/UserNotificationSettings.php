<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotificationSettings extends Model
{
    protected $table = 'user_notification_setting';
    public $timestamps = false ;
    protected $fillable = ['idNotification','value','idUser'];
}
