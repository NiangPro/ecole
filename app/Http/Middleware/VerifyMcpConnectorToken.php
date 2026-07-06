<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyMcpConnectorToken
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $configured = config('services.mcp_connector.token');

        if (empty($configured)) {
            Log::warning('MCP connector: MCP_CONNECTOR_TOKEN non configuré côté serveur.');

            return response()->json(['error' => 'server_misconfigured'], 500);
        }

        $header = (string) $request->header('Authorization', '');
        $token  = str_starts_with($header, 'Bearer ') ? substr($header, 7) : '';

        if ($token === '' || !hash_equals($configured, $token)) {
            Log::warning('MCP connector: jeton absent ou invalide.', ['ip' => $request->ip()]);

            return response()->json(['error' => 'unauthorized'], 401);
        }

        return $next($request);
    }
}
