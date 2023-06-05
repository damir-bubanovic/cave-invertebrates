<?php

use Damirbubanovic\CaveInvertebrates\App\Http\Controllers\AdminMenu;


function cave_menu() {
    AdminMenu::menu('Cave Title', 'Cave Inv', 'cave.html', 5);
}
add_action( 'admin_menu', 'cave_menu' );

