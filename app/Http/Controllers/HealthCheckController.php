<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class HealthCheckController extends Controller
{
    public function index(): JsonResponse
    {
        return new JsonResponse(['success' => true]);
    }
}
