<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/icommercegooglepay'], function (Router $router) {
    $router->bind('googlepayconfig', function ($id) {
        return app('Modules\Icommercegooglepay\Repositories\GooglepayConfigRepository')->find($id);
    });
    $router->get('googlepayconfigs', [
        'as' => 'admin.icommercegooglepay.googlepayconfig.index',
        'uses' => 'GooglepayConfigController@index',
        'middleware' => 'can:icommercegooglepay.googlepayconfigs.index'
    ]);
    $router->get('googlepayconfigs/create', [
        'as' => 'admin.icommercegooglepay.googlepayconfig.create',
        'uses' => 'GooglepayConfigController@create',
        'middleware' => 'can:icommercegooglepay.googlepayconfigs.create'
    ]);
    $router->post('googlepayconfigs', [
        'as' => 'admin.icommercegooglepay.googlepayconfig.store',
        'uses' => 'GooglepayConfigController@store',
        'middleware' => 'can:icommercegooglepay.googlepayconfigs.create'
    ]);
    $router->get('googlepayconfigs/{googlepayconfig}/edit', [
        'as' => 'admin.icommercegooglepay.googlepayconfig.edit',
        'uses' => 'GooglepayConfigController@edit',
        'middleware' => 'can:icommercegooglepay.googlepayconfigs.edit'
    ]);
    $router->put('googlepayconfigs', [
        'as' => 'admin.icommercegooglepay.googlepayconfig.update',
        'uses' => 'GooglepayConfigController@update',
        'middleware' => 'can:icommercegooglepay.googlepayconfigs.edit'
    ]);
    $router->delete('googlepayconfigs/{googlepayconfig}', [
        'as' => 'admin.icommercegooglepay.googlepayconfig.destroy',
        'uses' => 'GooglepayConfigController@destroy',
        'middleware' => 'can:icommercegooglepay.googlepayconfigs.destroy'
    ]);
// append

});
