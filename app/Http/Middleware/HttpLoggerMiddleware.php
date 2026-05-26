<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class HttpLoggerMiddleware
{
    const MAX_REQUESTS_BEFORE_ROTATE = 1000;
    const LOG_FILE = 'http_visits.log';

    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $logPath = storage_path('logs/' . self::LOG_FILE);

        // Create log file if it doesn't exist
        if (!File::exists($logPath)) {
            File::put($logPath, '');
        }

        // Get current request count
        $requestCount = cache()->increment('http_log_request_count');

        // Rotate log if needed
        if ($requestCount >= self::MAX_REQUESTS_BEFORE_ROTATE) {
            $this->rotateLog();
            cache()->put('http_log_request_count', 0);
        }

        // Prepare log data
        $logData = [
            'timestamp' => now()->toDateTimeString(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => $response->getStatusCode(),
            'duration' => microtime(true) - LARAVEL_START,
        ];

        // Write to log
        File::append($logPath, json_encode($logData) . PHP_EOL);
    }

    protected function rotateLog()
    {
        $logPath = storage_path('logs/' . self::LOG_FILE);
        $archivePath = storage_path('logs/http_visits_' . now()->format('Y-m-d_H-i-s') . '.log');

        if (File::exists($logPath)) {
            File::move($logPath, $archivePath);
        }
    }
}
