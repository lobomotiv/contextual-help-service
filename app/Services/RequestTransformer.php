<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Request;

class RequestTransformer
{
    public function transformToArray(Request $request): array
    {
        return [
            'url' => $request->url(),
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'segments' => $request->segments(),
            'query' => (string) $request->getQueryString(),
            'content' => (string) $request->getContent(),
        ];
    }
}
