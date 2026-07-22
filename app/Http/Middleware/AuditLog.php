<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditLog
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('PATCH') || $request->isMethod('DELETE')) {
            $this->logRequest($request, $response);
        }

        return $response;
    }

    protected function logRequest(Request $request, Response $response): void
    {
        try {
            ActivityLog::create([
                'auditable_type' => 'HTTP',
                'auditable_id' => null,
                'action' => strtolower($request->method()),
                'old_data' => null,
                'new_data' => [
                    'url' => $request->url(),
                    'method' => $request->method(),
                    'status' => $response->getStatusCode(),
                    'input' => $this->sanitizeInput($request->except(['password', 'password_confirmation', 'token'])),
                ],
                'user_id' => $request->user()?->id,
                'hotel_id' => $request->user()?->hotel_id ?? config('app.current_hotel_id'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Exception $e) {
            report($e);
        }
    }

    protected function sanitizeInput(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->sanitizeInput($value);
            }
        }

        return $data;
    }
}
