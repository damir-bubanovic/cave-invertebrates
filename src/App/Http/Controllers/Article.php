<?php

namespace Damirbubanovic\CaveInvertebrates\App\Http\Controllers;

use Damirbubanovic\CaveInvertebrates\Database\ArticlesTable;
use Damirbubanovic\CaveInvertebrates\Database\ArticlesAuthorsTable;
use Damirbubanovic\CaveInvertebrates\Database\ArticlesKeywordsTable;

use Damirbubanovic\CaveInvertebrates\App\Http\Middleware\Request;
use Damirbubanovic\CaveInvertebrates\App\Http\Middleware\Response;
use Damirbubanovic\CaveInvertebrates\App\Http\Middleware\Alert;

use Damirbubanovic\CaveInvertebrates\App\Http\Verification\Sanitize;

/**
 * Article
 */
class Article
{
    /**
     * [index description]
     * @return [type] [description]
     */
    public function index()
    {
        global $wpdb;
        $table_articles =  $wpdb->prefix . 'articles';
        $table_terms =  $wpdb->prefix . 'terms';

        $articles = $wpdb->get_results("
            SELECT $table_articles.*, $table_terms.name
            FROM  $table_articles, $table_terms
            WHERE $table_articles.category_id = $table_terms.term_id
            ORDER BY $table_articles.id DESC
        ");

        foreach ( $articles as $article ) {
            $article->authors = [];
            $article->keywords = [];
            $article->search = '';
        }
    
        $authors = new ArticlesAuthorsTable();
        $authorsList = $authors->index();

        foreach ($articles as $article) {
            foreach ($authorsList as $author) {
                if ($article->id === $author->article_id) {
                    array_push($article->authors, $author);
                }
            }
        }

        $keywords = new ArticlesKeywordsTable();
        $keywordsList = $keywords->index();

        foreach ($articles as $article) {
            foreach ($keywordsList as $keyword) {
                if ($article->id === $keyword->article_id) {
                    array_push($article->keywords, $keyword);
                }
            }
        }

        foreach ($articles as $article) {
            $a = implode(' | ', array_column($article->authors, 'display_name'));
            $k = implode(' | ', array_column($article->keywords, 'name'));
            $article->search = $article->title . ' | ' . $article->year . ' | ' . $article->name . ' | ' . $a . ' | ' . $k;
        }

        Response::json($articles);
        die();
    }

    /**
     * [store description]
     * @return [type] [description]
     */
    public static function store()
    {
        $reference = Sanitize::reference(Request::input('reference'));
        $authors = Request::input('authors');
        $year = Sanitize::year(Request::input('year'));
        $title = Sanitize::text(Request::input('title'));
        $keywords = Request::input('keywords');
        $link = Sanitize::url(Request::input('link'));
        $category = Request::input('category');

        $article = new ArticlesTable();
        $articleId = $article->store($title, $reference, $link, $category, $year);

        $authorsList = explode(',', $authors);
        foreach ($authorsList as $authorId) {
            $articlesAuthors = new ArticlesAuthorsTable();
            $articlesAuthors->store($articleId, $authorId);
        }

        $keywordsList = explode(',', $keywords);
        foreach ($keywordsList as $keywordId) {
            $articlesKeywords = new ArticlesKeywordsTable();
            $articlesKeywords->store($articleId, $keywordId);
        }

        $result = ['alert' =>  Alert::message('success', 'Article ' . $title . ' has been created!')];
        Response::json($result);
        die();
    }

    /**
     * [storeAll description]
     * @return [type] [description]
     */
    public function storeAll($title, $reference, $link, $categoryId, $year)
    {
        global $wpdb;
        $table_name =  $wpdb->prefix . 'articles';

        $wpdb->insert($table_name, array(
            'title'         =>  $title,
            'reference'     =>  $reference,
            'link'          =>  $link,
            'category_id'   =>  $categoryId,
            'year'          =>  $year
        ));

        $id = $wpdb->insert_id;

        return $id;
    }


    /**
     * [show description]
     * @return [type] [description]
     */
    public function show()
    {
        
    }

    /**
     * [update description]
     * @return [type] [description]
     */
    public function update()
    {
        $id = Sanitize::number(Request::input('id'));
        $title = Sanitize::text(Request::input('title'));
        $reference = Sanitize::text(Request::input('reference'));
        $link = Sanitize::url(Request::input('link'));
        $year = Sanitize::year(Request::input('year'));
        $category_id = Sanitize::number(Request::input('category_id'));
        $authors = Request::input('authors');
        $keywords = Request::input('keywords');


        $article = new ArticlesTable();
        $article->update($id, $title, $reference, $link, $year, $category_id);

        $author = new ArticlesAuthorsTable();
        $author->destroyAll($id);
        $author->updateAll($id, $authors);

        $keyword = new ArticlesKeywordsTable();
        $keyword->destroyAll($id);
        $keyword->updateAll($id, $keywords);

        $result = ['alert' =>  Alert::message('success', 'Article ' . $title . ' has been updated!')];
        Response::json($result);
    }

    /**
     * [destroy description]
     * @return [type] [description]
     */
    public function destroy()
    {
        $id = Sanitize::number(Request::input('id'));

        $article = new ArticlesTable();
        $article->destroyAll($id);

        $author = new ArticlesAuthorsTable();
        $author->destroyAll($id);

        $keyword = new ArticlesKeywordsTable();
        $keyword->destroyAll($id);

        $result = ['alert' =>  Alert::message('success', 'Article has been deleted!')];
        Response::json($result);
    }


}
