<?php

use Illuminate\Routing\Router;

$router->group(['prefix' => 'icommercegooglepay'], function (Router $router) {
    
    $router->post('/response', [
        'as' => 'icommercegooglepay.api.googlepay.response',
        'uses' => 'IcommerceGooglepayApiController@response',
    ]);

});