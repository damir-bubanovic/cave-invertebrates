<?php

namespace Damirbubanovic\CaveInvertebrates\App\Http\Middleware;

/**
 * 
 */
class Request
{
	public static function input($data)
	{
		return $_REQUEST[$data];
	}

}