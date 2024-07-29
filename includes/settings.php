<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

function r3d_postbox($r3d_postbox_title, $r3d_name)
{

	$r3d_postbox_id = 'flipbook-' . $r3d_name . '-options';

?>

	<div class="postbox closed">
		<div class="postbox-header">
			<h2 class="hndle ui-sortable-handle"><?php echo esc_html($r3d_postbox_title); ?></h2>
			<div class="handle-actions hide-if-no-js"><button type="button" class="handle-order-higher" aria-disabled="false" aria-describedby="submitdiv-handle-order-higher-description"><span class="screen-reader-text"><?php _e('Toggle panel:', 'real3d-flipbook');
																																																							echo esc_html(' ' . $r3d_postbox_title); ?></span><span class="toggle-indicator" aria-hidden="true"></span></button></div>
		</div>
		<div class="inside">
			<table class="form-table" id="<?php echo esc_attr($r3d_postbox_id); ?>">
				<tbody></tbody>
			</table>
			<div class="clear"></div>
		</div>
	</div>

<?php

}

$flipbook_global = get_option("real3dflipbook_global");

$flipbook_global_defaults = r3dfb_getDefaults();

$flipbook = r3d_array_merge_deep($flipbook_global_defaults, $flipbook_global);

?>

<div id='real3dflipbook-admin' style="display:none;">
	<a href="admin.php?page=real3d_flipbook_admin" class="back-to-list-link">&larr;
		<?php _e('Back to flipbooks list', 'flipbook'); ?>
	</a>
	<h1><?php _e('Global settings', 'real3d-flipbook'); ?></h1>
	<p><?php _e('Global default settings for all flipbooks', 'real3d-flipbook'); ?></p>
	<form method="post" id="real3dflipbook-options-form" enctype="multipart/form-data" action="admin-ajax.php?page=real3d_flipbook_admin&action=save_settings">
		<div>

			<h2 id="r3d-tabs" class="nav-tab-wrapper wp-clearfix">
				<?php
				
				?>
				<a href="#" class="nav-tab" data-tab="tab-general"><?php _e('General', 'real3d-flipbook'); ?></a>
				<a href="#" class="nav-tab" data-tab="tab-lightbox"><?php _e('Lightbox', 'real3d-flipbook'); ?></a>
				<a href="#" class="nav-tab" data-tab="tab-webgl"><?php _e('WebGL', 'real3d-flipbook'); ?></a>
				<a href="#" class="nav-tab" data-tab="tab-mobile"><?php _e('Mobile', 'real3d-flipbook'); ?></a>
				<a href="#" class="nav-tab" data-tab="tab-ui"><?php _e('UI', 'real3d-flipbook'); ?></a>
				<a href="#" class="nav-tab" data-tab="tab-menu"><?php _e('Menu Buttons', 'real3d-flipbook'); ?></a>
				<a href="#" class="nav-tab" data-tab="tab-translate"><?php _e('Translate', 'real3d-flipbook'); ?></a>
				<?php
				
				?>
				<a href="#" class="nav-tab" data-tab="tab-overrides"><?php _e('Overrides', 'real3d-flipbook'); ?></a>
				<?php
				
				?>
				<a href="#" class="nav-tab" data-tab="tab-advanced"><?php _e('Advanced', 'real3d-flipbook'); ?></a>
				<?php
				if (defined('R3D_PDF_TOOLS_VERSION')) {
					if (version_compare(R3D_PDF_TOOLS_VERSION, '2.0', '>=')) {
				?>
						<a href="#" class="nav-tab" data-tab="tab-pdf-tools"><?php _e('PDF Tools', 'real3d-flipbook'); ?></a>
				<?php
					}
				}
				
				?>

				<?php
				
				$tabs_html = '';
				// Use a filter to allow external plugins to add tabs.
				$tabs_html = apply_filters('r3d_settings_add_nav_tab', $tabs_html);
				echo ($tabs_html);
				
				?>
			</h2>

		</div>
		<div class="">
			<!-- removeIf(lite) -->
			<div id="tab-general" style="display:none;">
				<table class="form-table" id="flipbook-general-options">
					<tbody></tbody>
				</table>
			</div>
			<div id="tab-normal" style="display:none;">
				<table class="form-table" id="flipbook-normal-options">
					<tbody></tbody>
				</table>
			</div>
			<div id="tab-mobile" style="display:none;">
				<p class="description"><?php _e('Override settings for mobile devices (use different view mode, smaller textures ect)', 'real3d-flipbook'); ?></p>
				<table class="form-table" id="flipbook-mobile-options">
					<tbody></tbody>
				</table>
			</div>
			<div id="tab-lightbox" style="display:none;">
				<table class="form-table" id="flipbook-lightbox-options">
					<tbody></tbody>
				</table>
			</div>
			<div id="tab-webgl" style="display:none;">
				<table class="form-table" id="flipbook-webgl-options">
					<tbody></tbody>
				</table>
			</div>
			<div id="tab-menu" style="display:none;">
				<div class="meta-box-sortables">
					<h3><?php _e('Menu buttons', 'real3d-flipbook'); ?></h3>

					<?php

					r3d_postbox(__('Current page', 'real3d-flipbook'), 'currentPage');
					r3d_postbox(__('First page', 'real3d-flipbook'), 'btnFirst');
					r3d_postbox(__('Previous page', 'real3d-flipbook'), 'btnPrev');
					r3d_postbox(__('Next page', 'real3d-flipbook'), 'btnNext');
					r3d_postbox(__('Last page', 'real3d-flipbook'), 'btnLast');
					r3d_postbox(__('Autoplay', 'real3d-flipbook'), 'btnAutoplay');
					r3d_postbox(__('Zoom In', 'real3d-flipbook'), 'btnZoomIn');
					r3d_postbox(__('Zoom Out', 'real3d-flipbook'), 'btnZoomOut');
					r3d_postbox(__('Table of Contents', 'real3d-flipbook'), 'btnToc');
					r3d_postbox(__('Thumbnails', 'real3d-flipbook'), 'btnThumbs');
					r3d_postbox(__('Share', 'real3d-flipbook'), 'btnShare');
					r3d_postbox(__('Notes', 'real3d-flipbook'), 'btnNotes');
					r3d_postbox(__('Print', 'real3d-flipbook'), 'btnPrint');
					r3d_postbox(__('Download pages', 'real3d-flipbook'), 'btnDownloadPages');
					r3d_postbox(__('Download PDF', 'real3d-flipbook'), 'btnDownloadPdf');
					r3d_postbox(__('Sound', 'real3d-flipbook'), 'btnSound');
					r3d_postbox(__('Fullscreen', 'real3d-flipbook'), 'btnExpand');
					r3d_postbox(__('Select Tool', 'real3d-flipbook'), 'btnSelect');
					r3d_postbox(__('Search Button', 'real3d-flipbook'), 'btnSearch');
					r3d_postbox(__('Search Input', 'real3d-flipbook'), 'search');
					r3d_postbox(__('Bookmark', 'real3d-flipbook'), 'btnBookmark');
					r3d_postbox(__('Tools', 'real3d-flipbook'), 'btnTools');
					r3d_postbox(__('Close', 'real3d-flipbook'), 'btnClose');
					r3d_postbox(__('Social share buttons', 'real3d-flipbook'), 'share-buttons');

					?>

				</div>
			</div>


			<div id="tab-ui" style="display:none;">
				<div class="meta-box-sortables">

					<table class="form-table" id="flipbook-ui-options">
						<tbody></tbody>
					</table>
					<h3><?php _e('Advanced settings', 'real3d-flipbook'); ?></h3>
					<p><?php _e('Override layout and skin settings', 'real3d-flipbook'); ?></p>

					<?php

					r3d_postbox(__('Skin', 'real3d-flipbook'), 'skin');
					r3d_postbox(__('Flipbook background', 'real3d-flipbook'), 'bg');
					r3d_postbox(__('Top Menu', 'real3d-flipbook'), 'menu-bar-2');
					r3d_postbox(__('Bottom Menu', 'real3d-flipbook'), 'menu-bar');
					r3d_postbox(__('Buttons', 'real3d-flipbook'), 'menu-buttons');
					r3d_postbox(__('Floating Buttons (on transparent menu)', 'real3d-flipbook'), 'menu-floating');
					r3d_postbox(__('Side navigation buttons', 'real3d-flipbook'), 'side-buttons');
					r3d_postbox(__('Close lightbox button', 'real3d-flipbook'), 'close-button');
					r3d_postbox(__('Sidebar', 'real3d-flipbook'), 'sidebar');

					?>

				</div>
			</div>

			<div id="tab-translate" style="display:none;">
				<table class="form-table" id="flipbook-translate-options">
					<tbody></tbody>
				</table>
			</div>


			<div id="tab-advanced" style="display:none;">
				<table class="form-table" id="flipbook-advanced-options">
					<tbody></tbody>
				</table>
			</div>
			<!-- endRemoveIf(lite) -->
			<div id="tab-overrides" style="display:none;">
				<p class="description"><?php _e('Use Real3D Flipbook to show your existing PDF links or PDF Viewer / PDF Embedder / 3D Flipbook shortocodes.', 'real3d-flipbook'); ?></p>
				<p class="description"><?php _e('Just by enabling the option, Real3D Flipbok will be used instead of your old viewer.', 'real3d-flipbook'); ?></p>
				<table class="form-table" id="flipbook-overrides-options">
					<tbody></tbody>
				</table>
			</div>

			<?php
			if (defined('R3D_PDF_TOOLS_VERSION')) {
				if (version_compare(R3D_PDF_TOOLS_VERSION, '2.0', '>=')) {
			?>
					<div id="tab-pdf-tools" style="display:none;">
						<table class="form-table" id="flipbook-pdf-tools-options">
							<tbody></tbody>
						</table>
					</div>
			<?php
				}
			}
			?>

			<div id="tab-preview" style="display:none;">
				<table class="form-table" id="flipbook-preview-options">
					<tbody></tbody>
				</table>
			</div>

		</div>
</div>
<p id="r3d-save" class="submit">
	<span class="spinner"></span>
	<!-- <a class="update-all-flipbooks alignright" href='#'>Save this settings for all flipbooks</a> -->
	<input type="submit" name="btbsubmit" id="btbsubmit" class="alignright button save-button button-primary" value="Save">
	<a href="#" class="alignright flipbook-reset-defaults button button-secondary"><?php _e('Rest to defaults', 'real3d-flipbook'); ?></a>
</p>
<div id="r3d-save-holder" style="display: none;" />
</form>
</div>
<?php

wp_enqueue_media();
// add_thickbox(); 
wp_enqueue_script('alpha-color-picker');
wp_enqueue_style('alpha-color-picker');
wp_enqueue_script("real3d-flipbook-settings");
wp_enqueue_style('real3d-flipbook-admin');

wp_enqueue_script('sweet-alert-2');
wp_enqueue_style('sweet-alert-2');

$r3d_nonce = wp_create_nonce("r3d_nonce");
wp_localize_script('real3d-flipbook-settings', 'r3d_nonce', array($r3d_nonce));

$flipbook["globals"] = $flipbook_global;
$flipbook["globals_defaults"] = $flipbook_global_defaults;
wp_localize_script('real3d-flipbook-settings', 'options', array(json_encode($flipbook)));

wp_register_script("real3d-flipbook-check", $this->PLUGIN_DIR_URL . "js/check.js", array(), $this->PLUGIN_VERSION);
wp_enqueue_script('real3d-flipbook-check');

wp_localize_script('real3d-flipbook-check', 'r3d_data', array(get_option('r3d_key'), admin_url(), $this->products));
