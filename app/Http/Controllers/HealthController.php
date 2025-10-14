<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class HealthController extends Controller
{
    /**
     * Basic health check endpoint.
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'service' => 'cpv-suggest',
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Detailed readiness check.
     */
    public function ready(): JsonResponse
    {
        $checks = [];
        $allHealthy = true;

        // Check database connection
        try {
            DB::connection()->getPdo();
            $checks['database'] = 'ok';
        } catch (\Exception $e) {
            $checks['database'] = 'failed';
            $allHealthy = false;
        }

        // Check Redis connection
        try {
            Redis::connection()->ping();
            $checks['redis'] = 'ok';
        } catch (\Exception $e) {
            $checks['redis'] = 'failed';
            $allHealthy = false;
        }

        // Check if CPV codes are loaded
        try {
            $count = DB::table('cpv_codes')->count();
            $checks['cpv_codes'] = $count > 0 ? 'ok' : 'empty';
            if ($count === 0) {
                $allHealthy = false;
            }
        } catch (\Exception $e) {
            $checks['cpv_codes'] = 'failed';
            $allHealthy = false;
        }

        // Check Anthropic API configuration
        $checks['anthropic_configured'] = !empty(config('services.anthropic.key')) ? 'ok' : 'missing';
        if ($checks['anthropic_configured'] !== 'ok') {
            $allHealthy = false;
        }

        return response()->json([
            'status' => $allHealthy ? 'ready' : 'not_ready',
            'checks' => $checks,
            'timestamp' => now()->toIso8601String(),
        ], $allHealthy ? 200 : 503);
    }
}
