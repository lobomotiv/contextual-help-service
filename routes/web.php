<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/** @var \Laravel\Lumen\Routing\Router $router */
$router->get('/', static function () use ($router) {
    return $router->app->version();
});

$router->get('/healthcheck', 'HealthCheckController@index');

$router->get('/article/{id}', [
    'middleware' => ['jwt.auth'],
    'uses' => 'ArticleController@index',
]);

$router->get('/article/{articleId}/section/{sectionName}', [
    'middleware' => ['jwt.auth'],
    'uses' => 'SectionController@index',
]);
