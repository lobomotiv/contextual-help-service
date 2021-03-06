<?php

declare(strict_types=1);

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/article/{id}', [
    'middleware' => ['jwt.auth'],
    'uses' => 'ArticleController@index',
]);

$router->get('/article/{articleId}/section/{sectionName}', [
    'middleware' => ['jwt.auth'],
    'uses' => 'SectionController@index',
]);

$router->get('/article/{articleId}/rate', [
    'middleware' => ['jwt.auth'],
    'uses' => 'RatingController@get',
]);

$router->delete('/article/{articleId}/rate', [
    'middleware' => ['jwt.auth'],
    'uses' => 'RatingController@delete',
]);
