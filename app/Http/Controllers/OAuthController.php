<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OAuthController extends Controller
{
    public function callback(Request $request)
    {
        $code = $request->input('code');

        $response = Http::asForm()
            ->withOptions(['verify' => false])
            ->post(config('services.auth_2earn.token'), [
                'grant_type'    => 'authorization_code',
                'client_id'     => config('services.auth_2earn.client_id'),
                'client_secret' => config('services.auth_2earn.secret'),
                'code'          => $code,
                'redirect_uri'  => config('services.auth_2earn.redirect'),
            ]);

        Log::info('OAuth token response:', ['body' => $response->body()]);

        Log::info('Http', ['client_id' => config('services.auth_2earn.client_id'), 'secret' => config('services.auth_2earn.secret')]);
        Log::info('OAuth token response', ['response' => $response->body()]);

        if (!$response->ok()) {
            Log::alert('OAuth token retrieval failed', ['response' => $response->body()]);
            return response()->json([
                'error'   => 'unauthorized',
                'message' => 'Error while retrieving the token',
                'details' => $response->json()
            ], 401);
        }

        $data = $response->json();
        session(['token_responce' => $data]);

        $idToken = $data['id_token'] ?? null;

        if (!$idToken) {
            Log::alert('ID Token missing in OAuth response', ['response' => $response->body()]);
            return response()->json(['error' => 'invalid_id_token', 'message' => 'ID Token missing from the response'], 401);
        }

        $publicKey = file_get_contents(storage_path(config('services.auth_2earn.public_key_path')));

        try {
            $decoded = JWT::decode($idToken, new Key($publicKey, 'RS256'));
            $user_id = $decoded->sub;

            $user = User::where('id', $user_id)->first();

            if (!$user) {
                return redirect()->route('login', ['locale' => app()->getLocale()])->with('error', 'User not found');
            }

            Auth::login($user);
            return redirect()->route('main', ['locale' => app()->getLocale()]);

        } catch (\Exception $e) {
            Log::error('JWT Decode Error: ' . $e->getMessage());
            return response()->json(['error' => 'token_error', 'message' => $e->getMessage()], 401);
        }
    }
}
