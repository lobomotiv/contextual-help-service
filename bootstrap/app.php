<?php

use Bepsvpt\SecureHeaders\SecureHeadersServiceProvider;
use Illuminate\Redis\RedisServiceProvider;
use Middleware\Auth\Jwt\Providers\LumenServiceProvider;
use Nord\Lumen\Cors\CorsMiddleware;
use Nord\Lumen\Cors\CorsServiceProvider;
use NunoMaduro\PhpInsights\Application\Adapters\Laravel\InsightsServiceProvider;

require_once __DIR__.'/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

// $app->withFacades();

// $app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

 $app->middleware([
     CorsMiddleware::class,
 ]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\EventServiceProvider::class);
$app->register(CorsServiceProvider::class);
$app->register(LumenServiceProvider::class);
$app->register(RedisServiceProvider::class);
$app->register(SecureHeadersServiceProvider::class);

if (class_exists(InsightsServiceProvider::class)) {
    $app->register(InsightsServiceProvider::class);
}

/**
 * Load configurations
 */
$app->configure('cors');
$app->configure('database');
$app->configure('insights');
$app->configure('logging');
$app->configure('zendesk');

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
    'middleware' => ['jwt.auth'],
], function ($router) {
    require __DIR__ . '/../routes/api.php';
});

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__ . '/../routes/healthcheck.php';
});

return $app;
