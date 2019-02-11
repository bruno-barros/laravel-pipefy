<?php namespace EduardoAVargas\Pipefy\Facades;

use Illuminate\Support\Facades\Facade;

class Pipefy extends Facade {

    protected static function getFacadeAccessor() 
    {
    	return 'pipefy';
 	}
}