<?php

namespace App\Http\Controllers;

use Illuminate\Database\DatabaseManager;
use Illuminate\Http\JsonResponse;
use Psr\Log\LoggerInterface;
use Throwable;

class HealthCheckController extends Controller
{
    public function index(): JsonResponse
    {
       return new JsonResponse(['success' => true]);
    }
}
