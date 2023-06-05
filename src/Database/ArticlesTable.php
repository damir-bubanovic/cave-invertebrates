<?php

namespace Damirbubanovic\CaveInvertebrates\Database;


/**
 * Articles Table
 */
class ArticlesTable
{
    /**
     * [index description]
     * @return [type] [description]
     */
    public function index()
    {

    }

    /**
     * [store description]
     * @return [type] [description]
     */
    public static function store($title, $reference, $link, $categoryId, $year)
    {
        global $wpdb;
        $table_name =  $wpdb->prefix . 'articles';

        $wpdb->insert($table_name, array(
            'title'         =>  $title,
            'reference'     =>  $reference,
            'link'          =>  $link,
            'category_id'   =>  $categoryId,
            'year'          =>  $year,
        ));

        $id = $wpdb->insert_id;

        return $id;
    }

    /**
     * [store description]
     * @return [type] [description]
     */
    public static function storeAll()
    {

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
    public function update($id, $title, $reference, $link, $year, $category_id)
    {
        global $wpdb;
        $table_name =  $wpdb->prefix . 'articles';

        $wpdb->update( $table_name, array(
            'title'         =>  $title,
            'reference'     =>  $reference,
            'link'          =>  $link,
            'category_id'   =>  $category_id,
            'year'          =>  $year
        ), array('id' => $id) );
    }


    /**
     * [destroy description]
     * @return [type] [description]
     */
    public function destroy($id)
    {
        global $wpdb;
        $table_name =  $wpdb->prefix . 'articles';

        $wpdb->update( $table_name, array( 'category_id' => 1 ), array('category_id' => $id));
    }



    public function destroyAll($id)
    {
        global $wpdb;
        $table_name =  $wpdb->prefix . 'articles';

        $wpdb->delete( $table_name, array( 'id' =>  $id ) );
    }



	public static function up()
	{
		global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $charset_collate = $wpdb->get_charset_collate();
        
        $table_name =  $wpdb->prefix . 'articles';

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id int NOT NULL AUTO_INCREMENT,
            title varchar(255),
            reference text,
            link varchar(255),
            category_id int UNSIGNED NOT NULL,
            year int UNSIGNED NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        dbDelta( $sql );
	}

	public static function down()
	{
		global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $table =  $wpdb->prefix . 'articles';

        $sql = "DROP TABLE IF EXISTS $table";
        $wpdb->query($sql);
	}
}