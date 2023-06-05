<?php

/**
 * Plugin Name:       Cave Invertebrates Plugin
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Plugin for scientific cave invertebrate articles
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Damir Bubanovic
 * Author URI:        https://damirbubanovic.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://damirbubanovic.com/cave-xls
 * Text Domain:       cave-invertebrates
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
	die('No way Hose');
}


use Damirbubanovic\CaveInvertebrates\Database\ArticlesTable;
use Damirbubanovic\CaveInvertebrates\Database\ArticlesAuthorsTable;
use Damirbubanovic\CaveInvertebrates\Database\ArticlesKeywordsTable;


/**
 * Constants
 */
define('CAVE_INVERTEBRATES_PATH', plugin_dir_path(__FILE__));
define('CAVE_INVERTEBRATES_VIEWS_PATH', plugin_dir_path(__FILE__) . '/src/Views/');
define('CAVE_INVERTEBRATES_VIEWS_TABS_PATH', plugin_dir_path(__FILE__) . '/src/Views/Tabs/');
define('CAVE_INVERTEBRATES_URL', plugin_dir_url(__FILE__) . 'src/Views/');

if (!class_exists('CaveInvertebrates')) {

	/**
	 * CaveInvertebrates
	 */
	class CaveInvertebrates
	{
		public function __construct()
		{
			require_once(CAVE_INVERTEBRATES_PATH . '/vendor/autoload.php');
		}


		public function initialise()
		{
			add_action('admin_enqueue_scripts' , array($this , 'enqueue_menu') );
			add_action( 'wp_enqueue_scripts', array($this , 'enqueue_articles') );
			include_once(CAVE_INVERTEBRATES_PATH . '/src/cave-invertebrates-menu.php');
			include_once(CAVE_INVERTEBRATES_PATH . '/src/cave-articles-page.php');
			include_once(CAVE_INVERTEBRATES_PATH . '/src/cave-actions.php');
		}


		public static function activate()
		{
			ArticlesTable::up();
			ArticlesAuthorsTable::up();
			ArticlesKeywordsTable::up();
		}

		public static function uninstall()
		{
			ArticlesTable::down();
			ArticlesAuthorsTable::down();
			ArticlesKeywordsTable::down();
		}


		public function enqueue_menu($screen)
		{
			if('toplevel_page_sample-page' !== $screen) return;

			wp_enqueue_script('vue', 'https://unpkg.com/vue@3/dist/vue.global.prod.js');
			// wp_enqueue_script('vue', 'https://unpkg.com/vue@3/dist/vue.global.js');
			wp_enqueue_script('axios', 'https://unpkg.com/axios/dist/axios.min.js');
			wp_enqueue_script('vuetify-js', 'https://cdn.jsdelivr.net/npm/vuetify@3.1.1/dist/vuetify.min.js');
			wp_enqueue_script('papa-parse', 'https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.2/papaparse.min.js');
			wp_enqueue_style('vuetify-css', 'https://cdn.jsdelivr.net/npm/vuetify@3.1.1/dist/vuetify.min.css');
			wp_enqueue_style('material-design-icons', 'https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.1.96/css/materialdesignicons.min.css');
			wp_enqueue_script('cave', CAVE_INVERTEBRATES_URL . 'cave.js', [], '1.0', true);

		}

		public function enqueue_articles()
		{
			global $post;
     
			if ( is_admin() || ! is_singular() || ! has_shortcode( $post->post_content, 'cave_invertebrates_articles' ) ) {
		        return;
		    }

		  wp_enqueue_script('vue', 'https://unpkg.com/vue@3/dist/vue.global.prod.js');
		  // wp_enqueue_script('vue', 'https://unpkg.com/vue@3/dist/vue.global.js');
			wp_enqueue_script('axios', 'https://unpkg.com/axios/dist/axios.min.js');
			wp_enqueue_script('sweet-alert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11');
			wp_enqueue_script('cave', CAVE_INVERTEBRATES_URL . 'cave-article.js', [], '1.0', true);
		}

	}


	$caveInvertebrates = new CaveInvertebrates;
	$caveInvertebrates->initialise();

	register_activation_hook( __FILE__, 'CaveInvertebrates::activate' );
	register_uninstall_hook( __FILE__, 'CaveInvertebrates::uninstall' );

}
