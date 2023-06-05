<?php

namespace Damirbubanovic\CaveInvertebrates\App\Http\Controllers;

use Damirbubanovic\CaveInvertebrates\Database\ArticlesAuthorsTable;

use Damirbubanovic\CaveInvertebrates\App\Http\Middleware\Request;
use Damirbubanovic\CaveInvertebrates\App\Http\Middleware\Response;
use Damirbubanovic\CaveInvertebrates\App\Http\Middleware\Alert;

use Damirbubanovic\CaveInvertebrates\App\Http\Verification\Sanitize;

/**
 * Authors
 */
class Author
{
    /**
     * Authors List
     * @return [type] [description]
     */
    public function index()
    {
        $authors = get_users( array( 'fields' => array( 'id', 'display_name' ) ) );
        Response::json($authors);
        die();
    }

    /**
     * Store Author / User In WP_DB
     * - sanitize author field
     * - check if author already exists
     * @return [array] alert message
     */
    public static function store()
    {
        $result = [];

        $author = Sanitize::text(Request::input('name'));
        
        $user = username_exists( $author );

        if ( ! $user ) {
            $user = wp_insert_user( array(
                'user_login' => $author,
                'user_pass'  => null,
            ));
            $result = ['alert' =>  Alert::message('success', 'Author: ' . $author . ' has been created!')];
        } else {
            $result = ['alert' =>  Alert::message('warning', 'Author: ' . $author . ' already exists')];
        }

        Response::json($result);
        die();
    }

    /**
     * Stora All Authors
     * @return [array] [author ids]
     */
    public function storeAll($data)
    {
        $authorsIds = [];

        if (!empty($data)) {
            $authors = explode("|", $data);

            foreach ($authors as $author) {
                $name = Sanitize::csvlist($author);
                $user = username_exists( $name );

                if ( ! $user ) {
                     $user = wp_insert_user( array(
                        'user_login' => $name,
                        'user_pass'  => null,
                    ));
                }

                array_push($authorsIds, (int) $user);
            }
        }

        return $authorsIds;
    }

    public function show()
    {
        
    }

    /**
     * Update author
     * - cannot update user_login - be carefull
     * @return [type] [description]
     */
    public function update()
    {
        $author = Sanitize::text(Request::input('name'));
        $id = Sanitize::number(Request::input('id'));
        $lower = strtolower($author);
        $nice = str_replace(" ", "-", $lower);

        if ($id === 1) {
            $result = ['alert' =>  Alert::message('warning', 'Cannot update admin: ' . $author)];
        } else {
            $user = wp_update_user( array( 
                'ID'            =>  $id, 
                'user_nicename' =>  $nice,
                'display_name'  =>  $author,
            ) );

            if (!is_wp_error( $user )) {
                $result = ['alert' =>  Alert::message('success', $author . ' has been updated!')];
            } else {
                $result = ['alert' =>  Alert::message('warning', $author . ' has not been updated!')];
            }
        }

        Response::json($result);
        die();
    }

    /**
     * Destroy author
     * @return [type] [description]
     */
    public function destroy()
    {
        $id = Sanitize::number(Request::input('id'));

        if ($id === 1) {
            $result = ['alert' =>  Alert::message('warning', 'Cannot delete admin!')];
        } else {
            wp_delete_user( $id, null );
            $author = new ArticlesAuthorsTable();
            $author->destroy($id);
            $result = ['alert' =>  Alert::message('success', 'Author has been deleted!')];
        }

        Response::json($result);
        die();
    }


}