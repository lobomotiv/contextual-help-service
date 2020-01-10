<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Services\RequestTransformer;
use Middleware\Auth\Jwt\Events\JwtAuthFailure;
use Psr\Log\LoggerInterface;

class JwtAuthFailureListener
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var RequestTransformer
     */
    private $requestTransformer;

    public function __construct(LoggerInterface $logger, RequestTransformer $requestTransformer)
    {
        $this->logger = $logger;
        $this->requestTransformer = $requestTransformer;
    }

    public function handle(JwtAuthFailure $event): void
    {
        $this->logger->error(
            'JWT authentication failed',
            $this->requestTransformer->transformToArray($event->getRequest())
        );
    }
}
