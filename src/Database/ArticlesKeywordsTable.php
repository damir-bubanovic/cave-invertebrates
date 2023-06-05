<?php

namespace Damirbubanovic\CaveInvertebrates\Database;


/**
 * Articles Keywords Table
 */
class ArticlesKeywordsTable
{

    /**
     * [index description]
     * @return [type] [description]
     */
    public function index()
    {
        global $wpdb;
        $table_articles_keywords =  $wpdb->prefix . 'articles_keywords';
        $table_terms =  $wpdb->prefix . 'terms';

        $keywords = $wpdb->get_results("
            SELECT $table_articles_keywords.article_id, $table_articles_keywords.term_id, $table_terms.name
            FROM $table_articles_keywords
            JOIN $table_terms ON $table_articles_keywords.term_id = $table_terms.term_id
        ");

        return $keywords;
        die();
    }

    /**
     * [store description]
     * @return [type] [description]
     */
    public static function store($articleId, $keywordId)
    {
        global $wpdb;
        $table_name =  $wpdb->prefix . 'articles_keywords';

        if (!is_null($keywordId) || !empty($keywordId)) {
            $wpdb->insert($table_name, array(
                'article_id'    =>  $articleId,
                'term_id'       =>  $keywordId,
            ));
        }
    }

    /**
     * [store description]
     * @return [type] [description]
     */
    public static function storeAll($articleId, $keywordsIds)
    {
        global $wpdb;
        $table_name =  $wpdb->prefix . 'articles_keywords';

        if (!is_null($keywordsIds) || !empty($keywordsIds)) {
            foreach ($keywordsIds as $id) {
                $wpdb->insert($table_name, array(
                    'article_id'    =>  $articleId,
                    'term_id'    =>  $id,
                ));
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
    public function updateAll($articleId, $keywordIds)
    {
        global $wpdb;
        $table_name =  $wpdb->prefix . 'articles_keywords';

        $ids = explode(',', $keywordIds);
        foreach ($ids as $id) {
            $number = intval($id);
            $wpdb->insert($table_name, array(
                'article_id'    =>  $articleId,
                'term_id'       =>  $number,
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
        $table_name =  $wpdb->prefix . 'articles_keywords';

        $wpdb->delete( $table_name, array( 'term_id' =>  $id, ) );
    }

    public function destroyAll($id)
    {
        global $wpdb;
        $table_name =  $wpdb->prefix . 'articles_keywords';

        $wpdb->delete( $table_name, array( 'article_id' =>  $id, ) );
    }


	public static function up()
	{
		global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $charset_collate = $wpdb->get_charset_collate();
        
        $table =  $wpdb->prefix . 'articles_keywords';

        $sql = "CREATE TABLE IF NOT EXISTS $table (
            id int NOT NULL AUTO_INCREMENT,
            article_id int UNSIGNED NOT NULL,
            term_id int UNSIGNED NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";


        dbDelta( $sql );
	}

	public static function down()
	{
		global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $table =  $wpdb->prefix . 'articles_keywords';

        $sql = "DROP TABLE IF EXISTS $table";
        $wpdb->query($sql);
	}
}