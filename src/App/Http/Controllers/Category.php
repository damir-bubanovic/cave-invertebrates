<?php

namespace Damirbubanovic\CaveInvertebrates\App\Http\Controllers;

use Damirbubanovic\CaveInvertebrates\Database\ArticlesTable;

use Damirbubanovic\CaveInvertebrates\App\Http\Middleware\Request;
use Damirbubanovic\CaveInvertebrates\App\Http\Middleware\Response;
use Damirbubanovic\CaveInvertebrates\App\Http\Middleware\Alert;

use Damirbubanovic\CaveInvertebrates\App\Http\Verification\Sanitize;

/**
 * Category
 */
class Category
{
    /**
     * [index description]
     * @return [type] [description]
     */
    public function index()
    {
        $categories = get_categories(['hide_empty' => 0]);
        Response::json($categories);
        die();
    }

    /**
     * [store description]
     * @return [type] [description]
     */
    public function store()
    {
        $result = [];

        $category = Sanitize::text(Request::input('name'));

        if (!category_exists( $category )) {
            wp_create_category( $category );
            $result = ['alert' =>  Alert::message('success', 'Category: ' . $category . ' has been created!')];
        } else {
            $result = ['alert' =>  Alert::message('warning', 'Category: ' . $category . ' already exists')];
        }

        Response::json($result);
        die();
    }

    public function storeAll($data)
    {
        $categoryId;

        if (!empty($data)) {
            $category = Sanitize::text($data);

            if (!category_exists( $category )) {
                $categoryId = wp_create_category( $category );
            } else {
                $categoryId = get_cat_ID( $category );
            }
        } else {
            $categoryId = 1;
        }

        return $categoryId;
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
        $category = Sanitize::text(Request::input('name'));
        $id = Sanitize::number(Request::input('id'));
        $lower = strtolower($keyword);
        $slug = str_replace(" ", "-", $lower);

        if ($id === 1) {
            $result = ['alert' =>  Alert::message('warning', 'Uncategorized cannot be changed')];
        } else {
            $cat = wp_update_term( $id, 'category', array( 'name' => $category, 'slug' => $slug ) );

            if (!is_wp_error( $cat )) {
                $result = ['alert' =>  Alert::message('success', $category . ' has been updated!')];
            } else {
                $result = ['alert' =>  Alert::message('warning', $category . ' has not been updated!')];
            }
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

        if ($id === 1) {
            $result = ['alert' =>  Alert::message('warning', 'Uncategorized cannot be changed')];
        } else {
            $cat = wp_delete_term( $id, 'category' );

            if (!is_wp_error( $cat )) {
                $category = new ArticlesTable();
                $category->destroy($id);
                $result = ['alert' =>  Alert::message('success', 'Category has been deleted!')];
            } else {
                $result = ['alert' =>  Alert::message('warning', 'Category has not been deleted')];
            }
        }


        Response::json($result);
        die();
    }


}
