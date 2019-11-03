<?php namespace PipefyWrapper\Facades;

use Illuminate\Support\Facades\Facade;

class Pipefy extends Facade {

    protected static function getFacadeAccessor() 
    {
    	return 'pipefy';
 	}
}