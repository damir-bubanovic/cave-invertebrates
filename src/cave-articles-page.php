<?php

use Damirbubanovic\CaveInvertebrates\App\Http\Controllers\ArticlesPage;

function search_articles(){
	return ArticlesPage::articles();
}

add_shortcode('cave_invertebrates_articles', 'search_articles');
