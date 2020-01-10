<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Psr\Log\LoggerInterface;

class HealthCheckController extends Controller
{
    public function index(LoggerInterface $logger): JsonResponse
    {
        $logger->info('healthcheck was called');

        return new JsonResponse(['success' => true]);
    }
}
