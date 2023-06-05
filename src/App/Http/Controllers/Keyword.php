<?php

namespace Damirbubanovic\CaveInvertebrates\App\Http\Controllers;

use Damirbubanovic\CaveInvertebrates\Database\ArticlesKeywordsTable;

use Damirbubanovic\CaveInvertebrates\App\Http\Middleware\Request;
use Damirbubanovic\CaveInvertebrates\App\Http\Middleware\Response;
use Damirbubanovic\CaveInvertebrates\App\Http\Middleware\Alert;

use Damirbubanovic\CaveInvertebrates\App\Http\Verification\Sanitize;

/**
 * Keyword
 */
class Keyword
{
    /**
     * [index description]
     * @return [type] [description]
     */
    public function index()
    {
        $keywords = get_tags(['hide_empty' => 0]);
        Response::json($keywords);
        die();
    }

    /**
     * [store description]
     * @return [type] [description]
     */
    public static function store()
    {
        $result = [];

        $tag = Sanitize::text(Request::input('name'));
        

        if (!tag_exists( $tag )) {
            wp_create_term($tag);
            $result = ['alert' =>  Alert::message('success', 'Keyword: ' . $tag . ' has been created!')];
        } else {
            $result = ['alert' =>  Alert::message('warning', 'Keyword: ' . $tag . ' already exists')];
        }

        Response::json($result);
        die();
    }

    /**
     * Stora All Keywords
     * @return [array] [keyword ids]
     */
    public function storeAll($data)
    {
        $keywordsIds = [];

        if (!empty($data)) {
            $keywords = explode("|", $data);

            foreach ($keywords as $keyword) {
                $word = Sanitize::csvlist($keyword);
                $term = term_exists( $word, 'post_tag' );

                if ( ! $term ) {
                    $term = wp_insert_term( $word, 'post_tag' );
                }

                array_push($keywordsIds, (int) $term['term_id']);
            }
        }

        return $keywordsIds;
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
        $keyword = Sanitize::text(Request::input('name'));
        $id = Sanitize::number(Request::input('id'));
        $lower = strtolower($keyword);
        $slug = str_replace(" ", "-", $lower);

        $key = wp_update_term( $id, 'post_tag', array( 'name' => $keyword, 'slug' => $slug ) );

        if (!is_wp_error( $key )) {
            $result = ['alert' =>  Alert::message('success', $keyword . ' has been updated!')];
        } else {
            $result = ['alert' =>  Alert::message('warning', $keyword . ' has not been updated!')];
        }

        Response::json($result);
        die();
    }

    /**
     * [destroy description]
     * @return [type] [description]
     */
    public function destroy()
    {
        $id = Sanitize::number(Request::input('id'));

        $term = wp_delete_term( $id, 'post_tag' );
        if (!is_wp_error( $term )) {
            $keyword = new ArticlesKeywordsTable();
            $keyword->destroy($id);
            $result = ['alert' =>  Alert::message('success', 'Keyword has been deleted!')];
        } else {
            $result = ['alert' =>  Alert::message('warning', 'Keyword has not been deleted')];
        }

        Response::json($result);
        die();
    }


}
