<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Redis\RedisManager;
use Psr\Log\LoggerInterface;

class HealthCheckController extends Controller
{
    public function index(LoggerInterface $logger, RedisManager $redis): JsonResponse
    {
        try {
            $redis->ping();
            $logger->info('healthcheck was called');
            $responseBody = ['success' => true];
        } catch (\Exception $exception) {
            $logger->error('Redis is unavailable');
            $responseBody = ['success' => false, 'error' => 'Redis is unavailable'];
        }

        return new JsonResponse($responseBody);
    }
}
