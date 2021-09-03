<?php

/**
 * Provide the view for a metabox
 *
 * @link 		http://slushman.com
 * @since 		1.0.0
 *
 * @package 	Now_Hiring
 * @subpackage 	Now_Hiring/admin/partials
 */

//wp_nonce_field( $this->plugin_name, 'job_additional_info' );

$atts 					= array();
$atts['class'] 			= 'widefat';
$atts['description'] 	= '';
$atts['id'] 			= 'composer-name';
$atts['label'] 			= 'Composer name';
$atts['name'] 			= 'composer-name';
$atts['placeholder'] 	= '';
$atts['type'] 			= 'text';
$atts['value'] 			= '';

if ( ! empty( $this->meta[$atts['id']][0] ) ) {
	
	global $wpdb;

	$custom_table = $wpdb->prefix . 'wp_music';
	$name = 'composer-name';
	$post_id = get_the_ID();

	$meta_value = $wpdb->get_var("SELECT `$name` FROM  $custom_table where post_id = $post_id ");

	$atts['value'] = $meta_value;
}

//apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

?><p><?php

include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );

?></p>