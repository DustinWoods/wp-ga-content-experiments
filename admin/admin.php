<?php


require_once( WPGACXM_PLUGIN_ADMIN_INCLUDES_PATH . 'meta-boxes.php' );

Class WPgacxm_admin {

  public static $instance;

  public function __construct() {

    new WPgacxm_admin_metaBoxes();

    if(isset(self::$instance)) {
      //Throw error! we only want one instance
    } else {
      self::$instance = $this;
    }

    add_action( 'admin_init', array($this,'admin_add_posts_columns'), 10 );
    add_action( 'add_meta_boxes', array($this,'admin_add_meta_boxes') );
    add_action( 'init', array($this,'admin_enqueue_scripts') );

    //Setup ajax handler actions
    $this->setup_ajax_handlers();
  }

  private function setup_ajax_handlers() {
    add_action( 'wp_ajax_wpgacxm-create-experiment', array(WPgacxma::$instance,'ajax_create_experiment_post'), 1 );
  }

  //Let's setup some scripts!
  function admin_enqueue_scripts() {
    wp_register_script('wpgacxm_admin_scripts', WPGACXM_PLUGIN_JS_URL.'scripts.js', array('jquery-core')); //Register script
    wp_enqueue_script('wpgacxm_admin_scripts');

    wp_register_style('wpgacxm_admin_styles', WPGACXM_PLUGIN_CSS_URL.'style.css'); //Register script
    wp_enqueue_style('wpgacxm_admin_styles');

  }

  //Returns the post typyes that can run content experiments
  function get_enabled_post_types() {
    $post_types = get_post_types('','names');
    $post_types = apply_filters('wpgacxm_post_types', $post_types );
    return $post_types;
  }

  //Adds meta boxes to posts
  function admin_add_meta_boxes() {
    $post_types = $this->get_enabled_post_types();

    foreach ($post_types as $type ) {
      add_meta_box( 'wpgacxm-meta-box', __('GA Content Experiments', 'wpgacxm'), array(WPgacxm_admin_metaBoxes::$instance,'admin_post_meta_box'), $type, 'side', 'high' );
    }
  }

  //Adds colums to posts
  function admin_add_posts_columns() {
    //Show flags in list for all registered post types ( so also custom posts )
    $post_types = $this->get_enabled_post_types();

    foreach ($post_types as $type ) {
      add_action( "manage_${type}_posts_custom_column", array($this,'admin_add_experiment_column'), 10, 2);
      add_filter( "manage_${type}_posts_columns" , array($this,'admin_add_experiment_columns') );
    }
  }

  function admin_add_experiment_columns( $columns ) {
    $columns['experiments'] = __('Experiments','wpgacxm');
    return $columns;
  }

  //Determines if current user can publish experiments
  function can_user_publish() {
    $post_type_object = get_post_type_object('wpgacxm_experiment');
    return current_user_can($post_type_object->cap->publish_posts);
  }

  /*
   * add flags to single item
   */
  function admin_add_experiment_column( $col_name, $id ) {
    if( $col_name !== "experiments" ) return;
    echo "No experiments";
  }
}