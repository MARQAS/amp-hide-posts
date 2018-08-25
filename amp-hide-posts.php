<?php
/*
Plugin Name: AMP Hide Bulk Posts
Description: Hide All the Posts in AMP
Author: AMPforWP Team
Version: 1.1
Author URI: http://ampforwp.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter("redux/options/redux_builder_amp/sections", 'ampforwp_hide_bulk_posts_settings');
if ( ! function_exists( 'ampforwp_hide_bulk_posts_settings' ) ) {
	function ampforwp_hide_bulk_posts_settings($sections){

			$sections[] = array(
			      'title'       => __( 'AMP Hide Bulk Posts', 'amp-teaser' ),
			     // 'icon' => 'el el-view-mode',
				  'id'			=> 'ampforwp-hide-bulk-posts-subsection',
			      'desc'  		=> " ",
						);

			$sections[] = array(
				      		'title'     => __( 'Settings', 'amp-teaser' ),
		 					'id'				=> 'ampforwp-teaser-power',
				      		'subsection'=> true,
				      		'fields'	=>array(
				      			array(
		                           'id'       => 'ampforwp-posts-meta-default',
		                           'type'     => 'select',
		                           'title'    => __( 'Individual AMP Post (Bulk Edit)', 'accelerated-mobile-pages' ),
		                           'subtitle' => __( 'Allows you to Show or Hide AMP from All posts, so it can be changed individually later. This option will change the  Default value of AMP metabox in Posts', 'accelerated-mobile-pages' ),
		                           'desc' => __( 'NOTE: Changes will overwrite the previous settings.', 'accelerated-mobile-pages' ),
		                           'options'  => array(
		                               'show' => __('Show by Default', 'accelerated-mobile-pages' ),
		                               'hide' => __('Hide by default', 'accelerated-mobile-pages' ),
		                           ),
		                           'default'  => 'show',
		                           'required'=>array('amp-on-off-for-all-posts','=','1'),
		                        ),          
										
				      		)
			      	);

			return $sections;
	}
}
add_action('admin_head','ampforwp_change_default_amp_post_meta');
function ampforwp_change_default_amp_post_meta() {
	global $redux_builder_amp;
	// Posts
	$check_meta_post 		= get_option('ampforwp_default_posts_to');
	$checker_post			= 'show';
	$control_post			= $redux_builder_amp['ampforwp-posts-meta-default'];
	$meta_value_to_upate_post = 'default';
	if ( $control_post  === 'hide' ) {
		$checker_post				= 'hide';
		$meta_value_to_upate_post 	= 'hide-amp';
	}
	if ( $check_meta_post === $checker_post ) {
		return;
	}
	$posts = get_posts(array('post_type' => 'post', 'posts_per_page' => -1));
	foreach($posts as $post){
	    update_post_meta($post->ID,'ampforwp-amp-on-off', $meta_value_to_upate_post);
	}
	update_option('ampforwp_default_posts_to', $checker_post);
	return ;
}
/*
Test 1:
 	$check_meta_post = hide
	$checker_post = show
	$control_post = hide
	$meta_value_to_upate_post = default
		 
	condition1 $control_post = hide : true: 
		$checker_post = hide
		$meta_value_to_upate_post = hide-amp

	Condition2 $check_meta_post(hide) === $checker_post(hide): True
		return

 Test 2:
	$check_meta_post = hide
	$checker_post = show
	$control_post = show
	$meta_value_to_upate_post = default
		 
	condition1 $control_post != hide : false: 

	Condition2 $check_meta_post(hide) === $checker_post(show): false
	
	ampforwp-amp-on-off = default
	ampforwp_default_posts_to = shows
 */
