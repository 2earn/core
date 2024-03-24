<?php

namespace App\Http\Traits;

trait earnTrait
{

    public function randomNewPassword($length): string
    {
        $chars = "ABCDEFGHJKLMNPQRSTUVWXYZ123456789";
        $str = substr(str_shuffle($chars), 0, $length);
        return $str;
    }

    public function randomNewCodeOpt(): int
    {
        return rand(1000, 9999);
    }

    public function formatMobileNumber($mobile)
    {
        if ($mobile == null || $mobile == "")
            return;
        if ($mobile[0] == "0") {
            $mobile = substr($mobile, 1);
        }
        return $mobile;
    }
}
