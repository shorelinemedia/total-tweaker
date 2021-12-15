<?php
/*
 * Plugin Name:       Shoreline Total Tweaker
 * Plugin URI:        http://shoreline.media
 * Description:       Tweaks to the Total theme
 * Version:           1.0.1
 * Author:            Shoreline Media Marketing
 * Author URI:        http://shorline.media
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sl9-total-tweaks
 * Domain Path:       /languages
 * Github Plugin URI: https://github.com/shorelinemedia/total-tweaker
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define ( 'SL9_TOTAL_TWEAKER_VERSION', '1.0.1' );
define ( 'SL9_TOTAL_TWEAKER_PATH', plugin_dir_path( __FILE__ ) );
define ( 'SL9_TOTAL_TWEAKER_URL', plugin_dir_url( __FILE__ ) );

include_once SL9_TOTAL_TWEAKER_PATH . 'inc/classes/class-sl9-total-tweaker.php';

new SL9_Total_Tweaker( SL9_TOTAL_TWEAKER_VERSION );