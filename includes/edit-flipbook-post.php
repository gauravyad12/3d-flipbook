<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

$post_id = get_the_ID();
$current_id = get_post_meta($post_id, 'flipbook_id', true);


if (empty($current_id)) {
    $real3dflipbooks_ids = get_option('real3dflipbooks_ids');

    if (!empty($real3dflipbooks_ids)) {
        $real3dflipbooks_ids = array_map('intval', $real3dflipbooks_ids);
        $flipbook_id = max($real3dflipbooks_ids) + 1;
    } else {
        $flipbook_id = 1;
    }

    update_post_meta($post_id, 'flipbook_id', $flipbook_id);

    $real3dflipbooks_ids[] = $flipbook_id;
    update_option('real3dflipbooks_ids', $real3dflipbooks_ids);

		$flipbook = array(
			'id' => $flipbook_id
		);

} else {
    $flipbook_id = intval($current_id);
	$flipbook = get_option("real3dflipbook_" . $flipbook_id);

}





function r3d_postbox($r3d_postbox_title, $r3d_name)
{

	$r3d_postbox_id = 'flipbook-' . $r3d_name . '-options';
	$r3d_postbox_class = 'postbox closed';

?>

	<div class="<?php echo esc_attr($r3d_postbox_class); ?>">
		<div class="postbox-header">
			<h2 class="hndle ui-sortable-handle"><?php echo esc_html($r3d_postbox_title); ?></h2>
			<button type="button" class="handlediv" aria-disabled="false"><span class="screen-reader-text"><?php _e('Toggle panel:', 'real3d-flipbook');
																											echo esc_html(' ' . $r3d_postbox_title); ?></span><span class="toggle-indicator" aria-hidden="true"></span></button>
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


?>

<div id='real3dflipbook-admin' style="display:none;">

	<input type="hidden" name="id" value="<?php echo esc_attr($flipbook_id); ?>">
	<input type="hidden" name="bookId" value="<?php echo esc_attr($flipbook_id); ?>">

	<div>
		<h2 id="r3d-tabs" class="nav-tab-wrapper wp-clearfix">
			<a href="#" class="nav-tab" data-tab="tab-pages"><?php _e('Pages', 'real3d-flipbook'); ?></a>
			<a href="#" class="nav-tab" data-tab="tab-general"><?php _e('General', 'real3d-flipbook'); ?></a>
			<a href="#" class="nav-tab" data-tab="tab-toc"><?php _e('Table of Contents', 'real3d-flipbook'); ?></a>
			<a href="#" class="nav-tab" data-tab="tab-lightbox"><?php _e('Lightbox', 'real3d-flipbook'); ?></a>
			<a href="#" class="nav-tab" data-tab="tab-webgl"><?php _e('WebGL', 'real3d-flipbook'); ?></a>
			<a href="#" class="nav-tab" data-tab="tab-mobile"><?php _e('Mobile', 'real3d-flipbook'); ?></a>
			<?php
			
			?>
			<a href="#" class="nav-tab" data-tab="tab-ui"><?php _e('UI', 'real3d-flipbook'); ?></a>
			<?php
			
			?>
			<a href="#" class="nav-tab" data-tab="tab-menu"><?php _e('Menu Buttons', 'real3d-flipbook'); ?></a>
		</h2>
		<div id="tab-pages" style="display:none;">

			<!-- <p><?php _e("Select PDF or images from media library, or enter PDF URL.", "real3d-flipbook") ?></p> -->

			<table class="form-table">
				<tbody>

					<tr>
						<!-- <th><label><?php _e("Flipbook source", "real3d-flipbook") ?></label></th> -->
						<td>
							<button class='button-primary add-pages-button' id='r3d-select-source'><?php _e("Select PDF or images", "real3d-flipbook"); ?></button>
							<input type='text' class='regular-text' name="pdfUrl" id='r3d-pdf-source' placeholder="PDF URL">
							<button class='button-primary convert-button' id='r3d-convert' style="display: none;"><?php _e("Convert with PDF Tools", "real3d-flipbook"); ?></button>

							<p id="add-pages-description" class="description"><?php _e("Select PDF or images from media library, or enter PDF URL", "real3d-flipbook") ?></p>
							<p id="buy-pdf-tools" style="display:none;">

							<?php
								$message = sprintf(
									esc_html__('Optimize Real3D PDF Flipbooks with %1$s by converting PDF to images and JSON. Speed up the flipbook loading and secure the PDF.', 'real3d-flipbook'),
									'<a href="https://real3dflipbook.com/pdf-tools-addon/?ref=wp" style="text-decoration: none; font-weight: bold;" target="_blank">' . esc_html__('PDF Tools Addon for Real3D Flipbook', 'real3d-flipbook') . '</a>'
								);
								echo $message;
								?>
							</p>
							<p id="add-pages-info" class="description" style="display:none;"></p>
						</td>
					</tr>
				</tbody>
			</table>

			<div>
				<ul id="pages-container" tabindex="-1" class="attachments ui-sortable"></ul>
				<span class="delete-pages-button"><?php _e('Delete all pages', 'real3d-flipbook'); ?></span>
			</div>
		</div>

		<div id="tab-toc" style="display:none;">
			<p class="description"><?php _e('Create custom Table of Contents. This overrides default PDF outline or table of contents created by page titles.', 'real3d-flipbook'); ?></p>
			<p>
				<a class="add-toc-item button-primary" href="#"><?php _e('Add item', 'real3d-flipbook'); ?></a>
				<a href="#" type="button" class="button-link toc-delete-all"><?php _e('Delete all', 'real3d-flipbook'); ?></a>
			</p>
			<table class="form-table" id="flipbook-toc-options">
				<tbody></tbody>
			</table>
			<div id="toc-items" tabindex="-1" class="attachments ui-sortable"></div>
		</div>
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
		<div id="tab-ui" style="display:none;">
			<!-- <div id="poststuff"> -->
			<div class="meta-box-sortables">

				<?php
				
				?>

				<table class="form-table" id="flipbook-ui-options">
					<tbody></tbody>
				</table>
				<h3><?php _e('Advanced settings', 'real3d-flipbook'); ?></h3>
				<p><?php _e('Override layout and skin settings', 'real3d-flipbook'); ?></p>

				<?php
				
				?>

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
			<!-- </div> -->
		</div>
		<div id="tab-menu" style="display:none;">
			<!-- <div id="poststuff"> -->
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
	</div>
	<!--  <p id="r3d-save" class="submit">
            <span class="spinner"></span>
            <input type="submit" name="btbsubmit" class="alignright button save-button button-primary" value="Update" style="display:none;">
            <input type="submit" name="btbsubmit" class="alignright button create-button button-primary" value="Publish" style="display:none;">
            <a id="r3d-preview" href="#" class="alignright flipbook-preview button save-button button-secondary">Preview</a>
            <a href="#" class="alignright flipbook-reset-defaults button button-secondary">Reset all settings</a>
         </p> -->
	<!-- <div id="r3d-save-holder" style="display: none;" ></div> -->
	<!-- </form> -->
</div>
<!-- </div> -->
<div id="edit-page-modal-wrapper">

	<div tabindex="0" class="media-modal wp-core-ui" id="edit-page-modal" style="display: none;">

		<button type="button" class="media-modal-close STX-modal-close"><span class="media-modal-icon"><span class="screen-reader-text"><?php _e('Close media panel', 'real3d-flipbook'); ?></span></span></button>
		<div class="media-modal-content STX-modal-content">
			<div class="edit-attachment-frame mode-select hide-menu hide-router">

				<div class="edit-media-header">
					<button class="left dashicons"><span class="screen-reader-text"><?php _e('Edit previous media item', 'real3d-flipbook'); ?></span></button>
					<button class="right dashicons"><span class="screen-reader-text"><?php _e('Edit next media item', 'real3d-flipbook'); ?></span></button>
					<button type="button" class="media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text"><?php _e('Close dialog', 'real3d-flipbook'); ?></span></span></button>
				</div>

				<div class="media-frame-title STX-modal-title">
					<h1><?php _e('Edit page', 'real3d-flipbook'); ?></h1>
				</div>

				<div class="media-frame-content STX-modal-frame-content">

					<div class="page-editor">
						<div class="page-preview">
							<div class="thumbnail thumbnail-image">

								<img id="edit-page-img" draggable="false" alt="">

								<div class="attachment-actions">

									<button type="button" class="button replace-page"><?php _e('Replace image', 'real3d-flipbook'); ?></button>

								</div>
							</div>
						</div>
						<div class="page-editor-sidebar">

							<div class="settings">

								<div class="setting" data-setting="title">
									<label for="edit-page-title" class="name"><?php _e('Title', 'real3d-flipbook'); ?></label>
									<input type="text" id="edit-page-title" placeholder="Page title (for Table of Content)">
								</div>

								<div class="setting" data-setting="html-content">
									<label for="edit-page-html-content" class="name"><?php _e('HTML content', 'real3d-flipbook'); ?></label>
									<textarea id="edit-page-html-content" placeholder="Add any HTML content to page, set style and position with inline CSS"></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="media-modal-backdrop" style="display: none;"></div>
</div>

<?php

wp_enqueue_media();
add_thickbox();

wp_enqueue_script("real3d-flipbook-iscroll");
wp_enqueue_script("real3d-flipbook-pdfjs");
wp_enqueue_script("real3d-flipbook-pdfworkerjs");
wp_enqueue_script("real3d-flipbook-pdfservice");
wp_enqueue_script("real3d-flipbook-threejs");
wp_enqueue_script("real3d-flipbook-book3");
wp_enqueue_script("real3d-flipbook-bookswipe");
wp_enqueue_script("real3d-flipbook-webgl");
wp_enqueue_script("real3d_flipbook");
wp_enqueue_style('real3d-flipbook-style');

wp_enqueue_script('alpha-color-picker');
wp_enqueue_script('sweet-alert-2');
wp_enqueue_style('sweet-alert-2');

//include page editor if installed
if (defined('R3D_PAGE_EDITOR_VERSION')) {
	wp_enqueue_script('r3d-page-item');
	wp_enqueue_script('r3d-page-editor');
	wp_enqueue_style('r3d-page-editor');
}



if (defined('R3D_PDF_TOOLS_VERSION')) {
	if (version_compare(R3D_PDF_TOOLS_VERSION, '2.0', '>='))
		wp_enqueue_script('r3d-pdf-to-jpg');
}

wp_enqueue_script('real3d-flipbook-edit-post');
wp_enqueue_style('alpha-color-picker');
wp_enqueue_style('real3d-flipbook-admin');

$ajax_nonce = wp_create_nonce("saving-real3d-flipbook");



$flipbook['security'] = $ajax_nonce;

$flipbook_global = get_option("real3dflipbook_global");

$flipbook_global_defaults = r3dfb_getDefaults();

$flipbook['globals'] = r3d_array_merge_deep($flipbook_global_defaults, $flipbook_global);
$flipbook['globals']['plugins_url'] = plugins_url();
wp_localize_script('real3d-flipbook-edit-post', 'flipbook', array(json_encode($flipbook)));


wp_register_script("real3d-flipbook-check", $this->PLUGIN_DIR_URL . "js/check.js", array(), $this->PLUGIN_VERSION);
wp_enqueue_script('real3d-flipbook-check');

wp_localize_script('real3d-flipbook-check', 'r3d_data', array(get_option('r3d_key'), admin_url(), $this->products));

