<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class OAuthController extends Controller
{
    public function callback(Request $request)
    {
        Log::channel('auth')->info('OAuth Callback initiated', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'has_code' => $request->has('code'),
            'has_error' => $request->has('error'),
            'all_params' => $request->all()
        ]);

        $code = $request->input('code');

        $response = Http::asForm()
            ->withOptions(['verify' => false])
            ->post(config('services.auth_2earn.token'), [
                'grant_type' => 'authorization_code',
                'client_id' => config('services.auth_2earn.client_id'),
                'client_secret' => config('services.auth_2earn.secret'),
                'code' => $code,
                'redirect_uri' => config('services.auth_2earn.redirect'),
            ]);

        Log::channel('auth')->info('OAuth token response:', ['body' => $response->body()]);

        Log::channel('auth')->info('Http', ['client_id' => config('services.auth_2earn.client_id'), 'secret' => config('services.auth_2earn.secret')]);
        Log::channel('auth')->info('OAuth token response', ['response' => $response->body()]);

        if (!$response->ok()) {
            Log::alert('OAuth token retrieval failed', ['response' => $response->body()]);
            return response()->json([
                'error' => 'unauthorized',
                'message' => 'Error while retrieving the token',
                'details' => $response->json()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $response->json();
        session(['token_responce' => $data]);

        $idToken = $data['id_token'] ?? null;

        if (!$idToken) {
            Log::channel('auth')->alert('ID Token missing in OAuth response', ['response' => $response->body()]);
            return response()->json(['error' => 'invalid_id_token', 'message' => 'ID Token missing from the response'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $publicKey = file_get_contents(storage_path(config('services.auth_2earn.public_key_path')));

        try {
            $decoded = JWT::decode($idToken, new Key($publicKey, 'RS256'));
            $user_id = $decoded->sub;

            $user = User::where('id', $user_id)->first();

            if (!$user) {
                Log::channel('auth')->warning('User not found after OAuth login', ['sub' => $user_id]);
                return redirect()->route('login', ['locale' => app()->getLocale()])->with('error', 'User not found');
            }

            Auth::login($user);
            return redirect()->route('main', ['locale' => app()->getLocale()]);

        } catch (\Exception $e) {
            Log::channel('auth')->error('JWT Decode Error: ' . $e->getMessage());
            return response()->json(['error' => 'token_error', 'message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
