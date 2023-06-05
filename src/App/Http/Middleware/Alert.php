<?php

namespace Damirbubanovic\CaveInvertebrates\App\Http\Middleware;

/**
 * 
 */
class Alert
{
	public static function message($status, $message)
	{
		return ['status' => $status, 'message' => $message];
	}

}