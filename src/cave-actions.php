<?php

// namespace Damirbubanovic\CaveInvertebrates;


use Damirbubanovic\CaveInvertebrates\App\Http\Controllers\Article;
use Damirbubanovic\CaveInvertebrates\App\Http\Controllers\Author;
use Damirbubanovic\CaveInvertebrates\App\Http\Controllers\Keyword;
use Damirbubanovic\CaveInvertebrates\App\Http\Controllers\Category;
use Damirbubanovic\CaveInvertebrates\App\Http\Controllers\File;



/**
 * Article
 */
add_action( "wp_ajax_nopriv_article_list", array(new Article, 'index') );
add_action("wp_ajax_article_list", array(new Article, 'index'));
add_action("wp_ajax_article_create", array(new Article, 'store'));
add_action("wp_ajax_article_edit", array(new Article, 'update'));
add_action("wp_ajax_article_delete", array(new Article, 'destroy'));

/**
 * Author
 */
add_action("wp_ajax_author_list", array(new Author, 'index'));
add_action("wp_ajax_author_create", array(new Author, 'store'));
add_action("wp_ajax_author_edit", array(new Author, 'update'));
add_action("wp_ajax_author_delete", array(new Author, 'destroy'));


/**
 * Keyword
 */
add_action("wp_ajax_keyword_list", array(new Keyword, 'index'));
add_action("wp_ajax_keyword_create", array(new Keyword, 'store'));
add_action("wp_ajax_keyword_edit", array(new Keyword, 'update'));
add_action("wp_ajax_keyword_delete", array(new Keyword, 'destroy'));

/**
 * Category
 */
add_action("wp_ajax_category_list", array(new Category, 'index'));
add_action("wp_ajax_category_create", array(new Category, 'store'));
add_action("wp_ajax_category_edit", array(new Category, 'update'));
add_action("wp_ajax_category_delete", array(new Category, 'destroy'));


/**
 * File
 */
add_action("wp_ajax_file_create", array(new File, 'store'));

