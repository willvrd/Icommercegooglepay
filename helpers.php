<?php

use Modules\Icommercegooglepay\Entities\Googlepayconfig;

if (! function_exists('icommercegooglepay_get_configuration')) {

    function icommercegooglepay_get_configuration()
    {

    	$configuration = new Googlepayconfig();
    	return $configuration->getData();

    }

}

if (! function_exists('icommercegooglepay_get_entity')) {

	function icommercegooglepay_get_entity()
    {
    	$entity = new Googlepayconfig;
    	return $entity;	
    }

}
