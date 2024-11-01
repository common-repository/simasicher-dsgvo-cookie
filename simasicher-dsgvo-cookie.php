<?php
/*
 * Plugin Name:  SimaCookie
 * Plugin URI:   https://www.simasicher.com/simasicher-cookie-plugin/
 * Description:  Blockiere ohne Zustimmung alle Cookies und passe den Cookie-Hinweis deinen Wünschen an.
 * Version:      1.3.2
 * Author:       Simasicher
 * Author URI:   https://simasicher.com/unternehmen/
 * Contributors: Dominik Lohwieser, Sezgin Demircan, Robert Wenk
 * Text Domain:  simasicher-dsgvo-cookie
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $simaCookie_db_version;
$simaCookie_db_version = '1.0';

function ssdc_start() {

    load_plugin_textdomain( 'simasicher-dsgvo-cookie' );

    if ( is_admin() ) {
        require 'class-admin.php';
    } else {
        require 'class-frontend.php';
    }
} add_action('init', 'ssdc_start');

function ssdc_action_admin_init() {
    $arraya_ecl_v = get_plugin_data ( __FILE__ );
    $new_version = $arraya_ecl_v['Version'];

    if ( version_compare($new_version,  get_option('sima_version_number') ) == 1 ) {
        ssdc_check_defaults();
        update_option( 'sima_version_number', $new_version );
    }

    if ( ssdc_option('tinymcebutton') ) {
        require 'inc/tinymce.php';
    }
    $eda = __('Blockiere ohne Zustimmung alle Cookies und passe den Cookie-Hinweis deinen Wünschen an.', 'simasicher-dsgvo-cookie');
} add_action('admin_init', 'ssdc_action_admin_init');

function ssdc_check_defaults() { require 'defaults.php'; }

function ssdc_option($name) {
    $options = get_option('sima_eucookie');
    if ( isset( $options[$name] ) ) { return $options[$name]; }
    return false;
}

function simasicher_load_plugin_textdomain() {
    load_plugin_textdomain( 'simasicher-dsgvo-cookie', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'simasicher_load_plugin_textdomain' );
?>
