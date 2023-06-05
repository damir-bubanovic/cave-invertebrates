<?php

namespace Damirbubanovic\CaveInvertebrates\App\Http\Controllers;

use Damirbubanovic\CaveInvertebrates\App\Http\Middleware\Request;
use Damirbubanovic\CaveInvertebrates\App\Http\Middleware\Response;
use Damirbubanovic\CaveInvertebrates\App\Http\Middleware\Alert;

use Damirbubanovic\CaveInvertebrates\App\Http\Verification\Sanitize;

use Damirbubanovic\CaveInvertebrates\App\Http\Controllers\Author;
use Damirbubanovic\CaveInvertebrates\App\Http\Controllers\Keyword;
use Damirbubanovic\CaveInvertebrates\App\Http\Controllers\Category;
use Damirbubanovic\CaveInvertebrates\App\Http\Controllers\Article;

use Damirbubanovic\CaveInvertebrates\Database\ArticlesAuthorsTable;
use Damirbubanovic\CaveInvertebrates\Database\ArticlesKeywordsTable;


/**
 * File
 */
class File
{
    /**
     * Store File
     * @return [array] alert message
     */
    public static function store()
    {
        $reference = Sanitize::reference(Request::input('reference'));
        $authors = Request::input('authors');
        $year = Sanitize::year(Request::input('year'));
        $title = Sanitize::text(Request::input('title'));
        $keywords = Request::input('keywords');
        $link = Sanitize::url(Request::input('link'));
        $category = Sanitize::text(Request::input('category'));

        $author = new Author();
        $authorsIds = $author->storeAll($authors);

        $keyword = new Keyword();
        $keywordsIds = $keyword->storeAll($keywords);

        $cat = new Category();
        $categoryId = $cat->storeAll($category);

        $article = new Article();
        $articleId = $article->storeAll($title, $reference, $link, $categoryId, $year);

        $articleAuthors = new ArticlesAuthorsTable();
        $articleAuthors->storeAll($articleId, $authorsIds);

        $articleKeyword = new ArticlesKeywordsTable();
        $articleKeyword->storeAll($articleId, $keywordsIds);
    }

}