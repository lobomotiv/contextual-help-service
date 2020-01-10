<?php

declare(strict_types=1);

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/healthcheck', 'HealthCheckController@index');
