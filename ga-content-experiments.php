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


function wpgacxm_manage_posts_columns() {
  //Show flags in list for all registered post types ( so also custom posts )
  $post_types = get_post_types('','names');
  $post_types = apply_filters( 'wpgacxm_manage_post_types', $post_types );

  foreach ($post_types as $type ) {
    add_action( "manage_${type}_posts_custom_column", 'wpgacxm_admin_add_experiment_column', 10, 2);
    add_filter( "manage_${type}_posts_columns" , 'wpgacxm_admin_add_experiment_columns' );
  }
}

function wpgacxm_admin_add_experiment_columns( $columns ) {
	$columns['experiments'] = "Experiments";
	return $columns;
}


/*
 * add flags to single item
 */
function wpgacxm_admin_add_experiment_column( $col_name, $id ) {
  if( $col_name !== "experiments" ) return;
  echo "No experiments";
}
add_action( 'admin_init', 'wpgacxm_manage_posts_columns', 10 );