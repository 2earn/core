<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OAuthController extends Controller
{
    public function callback(Request $request)
    {
        $code = $request->input('code');
        $state = $request->input('state');

        $response = Http::asForm()
            ->withOptions(['verify' => false])
            ->withBasicAuth(config('services.auth_2earn.client_id'), config('services.auth_2earn.secret'))
            ->post(config('services.auth_2earn.token'), ['grant_type' => 'authorization_code', 'code' => $code, 'redirect_uri' => config('services.auth_2earn.redirect')]);

        if (!$response->ok()) {
            return response()->json(['error' => 'unauthorized', 'message' => trans('Error while retrieving the token')], 401);
        }

        session(['token_responce' => $response->json()]);

        $data = $response->json();
        $idToken = $data['id_token'] ?? null;

        if (!$idToken) {
            return response()->json(['error' => 'invalid_id_token', 'message' => trans('ID Token missing from the response')], 401);
        }

        $publicKey = file_get_contents(storage_path(config('services.auth_2earn.public_key_path')));

        try {
            $decoded = JWT::decode($idToken, new Key($publicKey, 'RS256'));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'invalid_token',
                'message' => trans('Invalid token') . ': ' . $e->getMessage()
            ], 401);
        }

        $user = User::find($decoded->sub);
        if ($user) {
            Auth::login($user);
        } else {
            abort(401, trans('User not found'));
        }

        return redirect('/home');
    }
}
