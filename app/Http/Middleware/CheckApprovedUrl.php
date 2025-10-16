<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckApprovedUrl
{

    public function handle(Request $request, Closure $next): Response
    {
        $approvedIps = [
            '127.0.0.1'
        ];

        $requestIp = $request->ip();

        Log::info('[CheckApprovedUrl] Checking request IP.', [
            'ip' => $requestIp,
            'approved_ips' => $approvedIps
        ]);

        if (!in_array($requestIp, $approvedIps)) {
            Log::warning('[CheckApprovedUrl] Unauthorized: Invalid IP.', ['ip' => $requestIp, 'approved_ips' => $approvedIps]);
            return response()->json(['error' => 'Unauthorized. Invalid IP.'], 403);
        }

        Log::info('[CheckApprovedUrl] Authorized IP.', ['ip' => $requestIp]);

        return $next($request);
    }
}
