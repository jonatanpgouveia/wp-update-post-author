<?php
/*
 *  Plugin Name: WP Update Post Author
 *  Plugin URI:  https://jonatangouveia.com
 *  Version:     1.0.0
 *  Author:      Jônatan Gouveia
 *  Author URI:  https://jonatangouveia.com
 *  License:     GPL2
 *  Text Domain: wp-update-post-author
 *  Domain Path: /languages
 *  Description: Plugin to bulk update all the actors of the WordPress post.
 */
// Link in het admin menu
if ( ! defined( 'UAP_PLUGIN_BASENAME' ) )
    define( 'UAP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'UAP_PLUGIN_NAME' ) )
    define( 'UAP_PLUGIN_NAME', trim( dirname( UAP_PLUGIN_BASENAME ), '/' ) );

if ( ! defined( 'UAP_PLUGIN_DIR' ) )
    define( 'UAP_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . UAP_PLUGIN_NAME );

if ( ! defined( 'UAP_PLUGIN_URL' ) )
    define( 'UAP_PLUGIN_URL', WP_PLUGIN_URL . '/' . UAP_PLUGIN_NAME );

function update_all_posts_plugin_path( $path = '' ) {
    return path_join( UAP_PLUGIN_DIR, trim( $path, '/' ) );
}

include_once(UAP_PLUGIN_DIR.'/include/functions.php');
/**
 * Set language file
 *
 */
load_plugin_textdomain('uap-move', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

function update_all_posts_plugin_url( $path = '' ) {
    return plugins_url( $path, UAP_PLUGIN_BASENAME );
}

add_action( 'admin_print_styles', 'update_all_posts_style_css' );

function update_all_posts_style_css() {
    wp_enqueue_style( 'uap_style', update_all_posts_plugin_url('css/bootstrap.min.css'));
}

add_action( 'admin_enqueue_scripts', 'update_all_posts_script_js' );

function update_all_posts_script_js($hook) {
    wp_enqueue_script('uap_script', update_all_posts_plugin_url('js/bootstrap.min.js'), array(), '1.0');
} 

add_action('admin_menu', 'update_all_posts_setup_menu');
 
function update_all_posts_setup_menu(){
    add_menu_page( 'Update All Posts Plugin Page', 
                   'Corrigir Posts', 
                   'manage_options', 
                   'update-all-posts', 
                   'update_all_posts_init' );
}

add_action( 'admin_init', 'submit_form_postall' );

function submit_form_postall()
{
  $page = (isset($_GET['page']) && $_GET['page'] === 'update-all-posts' );
   if ( $page && isset( $_POST['poststatus'] ) && $_POST['poststatus'] == 'poststatus' ) {
      $success = update_all_posts() ? 1 : 0;
      wp_redirect( admin_url( 'admin.php?page=update-all-posts&success=' . $success ) );
      exit;
   }
}