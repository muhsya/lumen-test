<?php

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['middleware' => 'auth'], function() use ($router) {
    $router->group(['prefix' => 'checklists'], function() use ($router) {
        $router->group(['prefix' => 'templates'], function() use ($router) {
            $router->get('/', ['uses' => 'TemplateController@index']);
            $router->get('/{id}', ['uses' => 'TemplateController@detail']);
            $router->post('/', ['uses' => 'TemplateController@store']);
            $router->post('/{id}', ['uses' => 'TemplateController@update']);
            $router->delete('/{id}', ['uses' => 'TemplateController@delete']);
        });

        $router->get('/', ['uses' => 'ChecklistController@index']);
        $router->post('/', ['uses' => 'ChecklistController@store']);
        $router->post('/{id}', ['uses' => 'ChecklistController@update']);
        $router->delete('/{id}', ['uses' => 'ChecklistController@delete']);

        $router->group(['prefix' => '/{id}'], function() use ($router) {
            $router->get('/', ['uses' => 'ChecklistController@detail']);

            $router->group(['prefix' => '/items'], function() use ($router) {
                $router->get('/', ['uses' => 'ItemController@getByChecklist']);
                $router->post('/_bulk', ['uses' => 'ItemController@updateBulk']);
            });
        });
    });

    $router->group(['prefix' => '/items'], function() use ($router) {
        $router->get('/', ['uses' => 'ItemController@index']);
        $router->get('/summaries', ['uses' => 'ItemController@summaries']);
        $router->get('/{id}', ['uses' => 'ItemController@detail']);
        $router->post('/', ['uses' => 'ItemController@store']);
        $router->post('/{id}', ['uses' => 'ItemController@update']);
        $router->delete('/{id}', ['uses' => 'ItemController@delete']);
    });
});
