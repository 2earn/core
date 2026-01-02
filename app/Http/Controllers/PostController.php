<?php

namespace App\Http\Controllers;

use App\Enums\TypeEventNotificationEnum;
use App\Enums\TypeNotificationEnum;
use App\Http\Traits\earnLog;
use App\Models\User;
use Carbon\Carbon;
use Core\Services\settingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    use earnLog;

    public function store(Request $request)
    {
        redirect()->route('home', app()->getLocale());
        $request->file('image1')->storeAs('profiles', 'front-id-image' . '998877' . '.png', 'public2');
    }

    public function verifyMail(Request $request, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        try {
            $userExisteMail = $settingsManager->getConditionalUser('email', $request->mail);
            if ($userExisteMail && $userExisteMail->idUser != $userAuth->idUser) {
                return 'no';
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return 'no';
        }
        return 'ok';
    }

    public function mailVerifOpt(Request $request, settingsManager $settingsManager)
    {
        $this->earnDebug("debut verification mail opt:");
        $userAuth = $settingsManager->getAuthUser();
        $this->earnDebug("connected user : " . $userAuth->id);
        $us = User::find($userAuth->id);
        $this->earnDebug("find user : " . $userAuth->id);
        if ($request->opt != $us->OptActivation) {
            $this->earnDebug("opt failed  : " . $request->opt);
            return 'no';
        }
        $this->earnDebug("change user information  : " . $request->opt);
        $us->email_verified = 1;
        $us->email = $request->mail;
        $us->email_verified_at = Carbon::now();
        $us->save();
        $this->earnDebug("opt ok  : " . $request->opt);
        return 'ok';

    }

    public function mailVerifNew(Request $request, settingsManager $settingsManager)
    {
        $userMail = $settingsManager->getUserById($settingsManager->getAuthUser()->id);
        if ($request->mail == $userMail->email)
            return 'no';
        return 'ok';

    }

    public function sendMail(Request $request, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        $opt = $settingsManager->randomNewCodeOpt();
        $us = User::find($userAuth->id);
        $us->OptActivation = $opt;
        $us->OptActivation_at = Carbon::now();
        $us->save();
        $settingsManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::VerifMail, ['msg' => $opt, 'type' => TypeNotificationEnum::SMS]);
        return 'ok';
    }

    public function getMember()
    {
        $pathFile = public_path() . '\assets\json\team-member-list.json';
        $contents = File::get($pathFile);
        $json = collect(json_decode($contents));
        return $json;
    }
}
