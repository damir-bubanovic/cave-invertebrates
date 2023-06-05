<?php

namespace Damirbubanovic\CaveInvertebrates\App\Http\Controllers;

/**
 * Articles Page
 */
class ArticlesPage
{
	public static function articles()
	{
		include_once(CAVE_INVERTEBRATES_VIEWS_PATH . 'cave-article.html');
	}
}