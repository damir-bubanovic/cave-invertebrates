<?php

namespace Damirbubanovic\CaveInvertebrates\Database;


/**
 * Article Authors Table
 */
class ArticlesAuthorsTable
{
    /**
     * [index description]
     * @return [type] [description]
     */
    public function index()
    {
        global $wpdb;
        $table_articles_authors =  $wpdb->prefix . 'articles_authors';
        $table_users =  $wpdb->prefix . 'users';

        $authors = $wpdb->get_results("
            SELECT $table_articles_authors.article_id, $table_articles_authors.user_id, $table_users.display_name
            FROM $table_articles_authors
            JOIN $table_users ON $table_articles_authors.user_id = $table_users.ID
        ");

        return $authors;
        die();
    }

    /**
     * [store description]
     * @return [type] [description]
     */
    public static function store($articleId, $authorId)
    {
        global $wpdb;
        $table_name =  $wpdb->prefix . 'articles_authors';

        if (!is_null($authorId) || !empty($authorId)) {
            $wpdb->insert($table_name, array(
                'article_id'    =>  $articleId,
                'user_id'       =>  $authorId,
            ));
        }
    }

    /**
     * [store description]
     * @return [type] [description]
     */
    public static function storeAll($articleId, $authorsIds)
    {
        global $wpdb;
        $table_name =  $wpdb->prefix . 'articles_authors';

        if (!empty($authorsIds)) {
            foreach ($authorsIds as $id) {
                if (!is_null($id) || !empty($id)) {
                    $wpdb->insert($table_name, array(
                        'article_id'    =>  $articleId,
                        'user_id'       =>  $id,
                    ));
                }
            }
        }
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

    }


    /**
     * [update description]
     * @return [type] [description]
     */
    public function updateAll($articleId, $authorsIds)
    {
        global $wpdb;
        $table_name =  $wpdb->prefix . 'articles_authors';

        $ids = explode(',', $authorsIds);
        foreach ($ids as $id) {
            $number = intval($id);
            $wpdb->insert($table_name, array(
                'article_id'    =>  $articleId,
                'user_id'       =>  $number,
            ));
        }
    }


    /**
     * [destroy description]
     * @return [type] [description]
     */
    public function destroy($id)
    {
        global $wpdb;
        $table_name =  $wpdb->prefix . 'articles_authors';

        $wpdb->delete( $table_name, array( 'user_id' =>  $id, ) );
    }


    public function destroyAll($id)
    {
        global $wpdb;
        $table_name =  $wpdb->prefix . 'articles_authors';

        $wpdb->delete( $table_name, array( 'article_id' =>  $id, ) );
    }


	public static function up()
	{
		global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $charset_collate = $wpdb->get_charset_collate();
        
        $table =  $wpdb->prefix . 'articles_authors';

        $sql = "CREATE TABLE IF NOT EXISTS $table (
            id int NOT NULL AUTO_INCREMENT,
            article_id int UNSIGNED NOT NULL,
            user_id int UNSIGNED NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        dbDelta( $sql );
	}

	public static function down()
	{
		global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $table =  $wpdb->prefix . 'articles_authors';

        $sql = "DROP TABLE IF EXISTS $table";
        $wpdb->query($sql);		
	}
}