<?php

namespace Damirbubanovic\CaveInvertebrates\App\Http\Middleware;

/**
 * 
 */
class Response
{
	public static function json($data)
	{
		wp_send_json( $data, null, $options );
	}
}