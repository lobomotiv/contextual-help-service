<?php

namespace Test\Helpers;

use Middleware\Auth\Jwt\Services\TokenEncoder;

trait JwtAuth
{
    private $ADMIN_ID = 4334;
    private $CUSTOMER_ID = 6546887;

    private function generateJwtToken(array $payload): string
    {
        return app()->get(TokenEncoder::class)->encode($payload);
    }

    private function generateJwtAuthHeader(array $payload): array
    {
        $token = $this->generateJwtToken($payload);

        return ['Authorization' => "Bearer {$token}"];
    }

    private function generateInvalidJwtHeader(): array
    {
        return ['Authorization' => "Bearer InvalidToken"];
    }

    private function generateValidJwtHeader(): array
    {
        return $this->generateJwtAuthHeader(['adminId' => $this->ADMIN_ID, 'customerId' => $this->CUSTOMER_ID]);
    }
}
