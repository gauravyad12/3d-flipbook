<?php

function r3d_user_has_woo_subscription()
{
	if (!is_user_logged_in()) return false;
	$user_id = get_current_user_id();
	if ($user_id == 0) return false;

	if (function_exists('wcs_user_has_subscription')) {
		$has_active_subscription = wcs_user_has_subscription($user_id, '', 'active');
		$has_pending_cancellation_subscription = wcs_user_has_subscription($user_id, '', 'pending-cancel');

		return $has_active_subscription || $has_pending_cancellation_subscription;
	}

	return false;
}

$show_flipbook = true;

$r3d_post_id = get_the_ID();
$r3d_id = get_post_meta($r3d_post_id, 'flipbook_id', true);

$flipbook = get_option('real3dflipbook_' . $r3d_id);
$flipbook_global_options = get_option("real3dflipbook_global", array());
$flipbook = array_merge($flipbook_global_options, $flipbook);

if (isset($flipbook['access'])) {

	if ($flipbook['access'] == 'woo_subscription')

		$show_flipbook = r3d_user_has_woo_subscription();

	else if ($flipbook['access'] == 'none')

		$show_flipbook = false;
}

if ($show_flipbook) {

	if (isset($flipbook['mode']) && $flipbook['mode'] === 'fullscreen') {
		// Inline CSS to hide common header and footer selectors
		echo '<style>
				#header, .header, #footer, .footer,
				.site-header, #site-header, .main-header, #main-header,
				.top-header, #top-header, .page-header, #masthead,
				.site-footer, #site-footer, .main-footer, #main-footer,
				.bottom-footer, #bottom-footer, .page-footer, #colophon {
					display: none;
				}
			</style>
			';
	}
	get_header();
	echo do_shortcode('[real3dflipbook id="' . $r3d_id . '"]');
	get_footer();
} else {

	esc_html_e('Forbidden');
}
