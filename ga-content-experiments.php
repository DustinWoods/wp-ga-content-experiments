<?php

/*
Plugin Name: Google Analytics Content Experiments
Plugin URI: http://dustinwoods.net/
Description: A/B Testing using Google Analytics Content Experiments
Version: 0.1
Author: Dustin Woods
Author URI: http://dustinwoods.net/
*/

/**
 * Compatibility Goal:
 * 	wpgacxm should be compatible with
 * 		Wordpress SEO (Yoast)
 * 		All custom post types
 * 		W3 Total Cache (or similar cache plugin)
 * 			Must support enhanced cache modes (ie htaccess rewrites to *.html)
 * 		Multilingual Supprt (Low Priority)
 * 		
 */

// Make sure we don't expose any info if called directly
if ( ! defined( 'ABSPATH' ) ) {
  echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
  exit;
}

//Define some useful stuff
define( 'WPGACXM_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'WPGACXM_PLUGIN_INCLUDES_PATH', WPGACXM_PLUGIN_PATH . trailingslashit ( 'includes' ) );
define( 'WPGACXM_PLUGIN_ADMIN_PATH', WPGACXM_PLUGIN_PATH . trailingslashit ( 'admin' ) );
define( 'WPGACXM_PLUGIN_ADMIN_INCLUDES_PATH', WPGACXM_PLUGIN_ADMIN_PATH . trailingslashit ( 'includes' ) );
define( 'WPGACXM_PLUGIN_ADMIN_SCRIPTS_PATH', WPGACXM_PLUGIN_ADMIN_PATH . trailingslashit ( 'js' ) );

define( 'WPGACXM_PLUGIN_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'WPGACXM_PLUGIN_ADMIN_URL', WPGACXM_PLUGIN_URL . trailingslashit ( 'admin' ) );
define( 'WPGACXM_PLUGIN_JS_URL', WPGACXM_PLUGIN_ADMIN_URL . trailingslashit( 'js' ) );
define( 'WPGACXM_PLUGIN_CSS_URL', WPGACXM_PLUGIN_ADMIN_URL . trailingslashit( 'css' ) );

//Are we admin? Let's initialize admin functionality
if( is_admin() ) {
  require_once( WPGACXM_PLUGIN_ADMIN_PATH . 'admin.php' );
}

require_once(WPGACXM_PLUGIN_INCLUDES_PATH . 'WPgacxmaExperiment.class.php');

Class WPgacxma {

  private static $instance;

  private function __construct() {
    add_action( 'init', array($this,'admin_add_experiment_post_type'));
  }

  public static function get_instance() {

    if(!isset(self::$instance)) {
      self::$instance = new self();
    }

    return self::$instance;
    
  }

  //Setup custom "experiment" post type
  function admin_add_experiment_post_type() {
    //Post type wpgacxm_experiment is used to record each experiment 
    register_post_type( 'wpgacxm_experiment',
      array(
        'labels' => array(
          'name' => __( 'GA Content Experiments', 'wpgacxm' ),
          'singular_name' => __( 'GA Content Experiment' )
        ),
        'public' => false,
        'has_archive' => true,
      )
    );
    //These status mirror GA's experiment statuses
    $statuses = array(
      'draft' => 'Draft',
      'ready_to_run' => 'Ready',
      'running' => 'Running',
      'ended' => 'Ended'
    );
    foreach ($statuses as $slug => $label) {
      register_post_status( $slug, array(
        'label'                     => __( $label, 'wpgacxm' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true
      ));
    }
  }

  //Gets the experiment associated with post of post_id
  public function get_current_experiment_post($post_id) {
    $args = array(
      'post_parent' => $post_id,
      'post_type'   => 'wpgacxm_experiment', 
      'posts_per_page' => -1,
      'post_status' => array('draft','ready_to_run','running')
    );

    $experiment_post = array_values(get_children( $args ));

    if(isset($experiment_post[0])) {
      return $experiment_post[0];
    }
    return false;
  }

}

//Initializes plugin main class
WPgacxma::get_instance();