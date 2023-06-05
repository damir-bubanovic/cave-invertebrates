<?php

namespace Damirbubanovic\CaveInvertebrates\App\Http\Verification;

/**
 * Sanitize user input
 */
class Sanitize
{
	public static function text($data)
	{
		$trim = trim($data);
		$first = ucfirst($trim);
		$clean = sanitize_text_field($first);

		return $clean;
	}

	public static function csvlist($data)
	{
		$whitespace = preg_replace('/\s/u', ' ', $data);
		$trim = trim($whitespace);
		$lower = strtolower($trim);
		$allFirst = ucwords($lower);

		$clean = sanitize_text_field($allFirst);

		return $clean;
	}

	public static function url($data)
	{
		$trim = trim($data);
		$clean = sanitize_url($trim);

		return $clean;
	}

	public static function reference($data)
	{
		$trim = trim($data);
		$clean = sanitize_text_field($trim);

		return $clean;
	}

	public static function year($data)
	{
		if (is_numeric($data) && strlen($data) == 4) {
			$clean = $data;
		} else {
			$clean = 0000;
		}

		return $clean;
	}

	public static function number($data)
	{
		$trim = trim($data);
		$clean = intval($trim);

		return $clean;
	}

}