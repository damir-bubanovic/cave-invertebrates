<?php

namespace Damirbubanovic\CaveInvertebrates\App\Http\Controllers;


/**
 * Admin Menu
 */
class AdminMenu {

    private $pageTitle;
    private $menuTitle;
    private $file;
    private $position;

    static $capability = 'manage_options';
    static $dashicon = 'dashicons-buddicons-replies';


    public function __construct($pageTitle, $menuTitle, $file, $position)
    {
        $this->pageTitle = $pageTitle;
        $this->menuTitle = $menuTitle;
        $this->file = $file;
        $this->position = $position;
    }


    public function create_view()
    {
        include_once(CAVE_INVERTEBRATES_VIEWS_PATH . $this->file);
    }


    public function create_menu()
    {
        add_menu_page(
            __( $this->pageTitle, 'my-textdomain' ),
            __( $this->menuTitle, 'my-textdomain' ),
            self::$capability,
            'sample-page',
            array( $this, 'create_view' ),
            self::$dashicon,
            $this->position
        );
    }


    public static function menu($pageTitle, $menuTitle, $file, $position)
    {
        return (new self($pageTitle, $menuTitle, $file, $position))->create_menu();
    }


}


