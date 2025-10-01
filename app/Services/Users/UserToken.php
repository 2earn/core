<?php

namespace App\Services\Users;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\PersonalAccessToken;

class UserToken
{
    public function getValidToken($user)
    {
        $tokenValue = Session::get('user_token');

        if ($tokenValue) {
            $tokenRecord = PersonalAccessToken::findToken($tokenValue);

            if (
                $tokenRecord &&
                $tokenRecord->tokenable_id === $user->id
            ) {
                $expiresAt = $tokenRecord->expires_at;

                if (!$expiresAt || Carbon::parse($expiresAt)->gt(now()->addMinutes(30))) {
                    return $tokenValue;
                }

                $tokenRecord->delete();
            }

            Session::forget('user_token');
        }

        return null;
    }

    public function createNewToken($user)
    {
        $newTokenResult = $user->createToken('user_token');
        $accessToken = $newTokenResult->accessToken;

        $accessToken->expires_at = now()->addHours(4);
        $accessToken->save();

        $plainTextToken = $newTokenResult->plainTextToken;
        Session::put('user_token', $plainTextToken);

        return $plainTextToken;
    }

    public  function getOrCreateToken()
    {
        return $this->getValidToken(auth()->user()) ?? $this->createNewToken(auth()->user());
    }
}
