<?php

use Illuminate\Routing\Router;

    $router->group(['prefix'=>'icommercegooglepay'],function (Router $router){
        $locale = LaravelLocalization::setLocale() ?: App::getLocale();

        $router->get('/', [
            'as' => 'icommercegooglepay',
            'uses' => 'PublicController@index',
        ]);
        
    });