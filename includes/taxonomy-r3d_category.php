<?php 

get_header();

?>
<div style="text-align: center;">
<?php 
function r3d_user_has_woo_subscription(){
	if ( ! is_user_logged_in() ) return false;
	$user_id = get_current_user_id();
	if( $user_id == 0 ) return false;
	if(function_exists('wcs_user_has_subscription'))
	return wcs_user_has_subscription( $user_id, '', 'active' );
	return false;
}

$query_args = array(
	'post_type' => 'r3d',
	'post_status' => 'publish',
	// 'posts_per_page' => '-1',
	'tax_query' => array(
		array(
			'taxonomy' => 'r3d_category',
			'field' => 'term_id',
			'terms' => get_queried_object_id(),
		)
	)
);

$query = new WP_Query($query_args);

$flipbook_global_options = get_option("real3dflipbook_global", array());

while ($query->have_posts()) {

	$query->the_post();
	$post_id = get_the_ID();
	$flipbook_id = get_post_meta($post_id, 'flipbook_id', true);

	$flipbook = get_option('real3dflipbook_' . $flipbook_id);
	$flipbook = r3d_array_merge_deep($flipbook_global_options, $flipbook);

	$show_flipbook = true;

	if ( isset($flipbook['access'])) {

		if($flipbook['access'] == 'woo_subscription')

			$show_flipbook = r3d_user_has_woo_subscription();

		else if ($flipbook['access'] == 'none')

			$show_flipbook = false;

	}

	if($show_flipbook){

		$shortcode = '[real3dflipbook id="'.$flipbook_id.'" mode="lightbox"]';
	  
		echo do_shortcode($shortcode);
		
	}

	wp_reset_postdata();

}

?>
</div>
<?php 

get_footer();





