<?php if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

$urls = [
	"bundle" => [
		"buy" => "https://checkout.freemius.com/mode/dialog/bundle/15216/plan/25359/",
	],
	"pdfTools" => [
		"buy" => "https://checkout.freemius.com/mode/dialog/plugin/15120/plan/25254/licenses/1/",
		"info" => "https://real3dflipbook.com/pdf-tools-addon/"
	],
	"pageEditor" => [
		"buy" => "https://checkout.freemius.com/mode/dialog/plugin/15210/plan/25350/",
		"info" => "https://real3dflipbook.com/page-editor-addon-for-real-3d-flipbook/"
	],
	"bookShelf" => [
		"buy" => "https://checkout.freemius.com/mode/dialog/plugin/15206/plan/25346/",
		"info" => "https://real3dflipbook.com/real3d-flipbook-bookshelf-addon/"
	],
	"elementor" => [
		"buy" => "https://checkout.freemius.com/mode/dialog/plugin/15213/plan/25354/",
		"info" => "https://real3dflipbook.com/elementor-addon/"
	],
	"wooCommerce" => [
		"buy" => "https://checkout.freemius.com/mode/dialog/plugin/15212/plan/25351/",
		"info" => "https://real3dflipbook.com/woocommerce-addon/"
	],
	"wpBakery" => [
		"buy" => "https://checkout.freemius.com/mode/dialog/plugin/15214/plan/25355/",
		"info" => "https://real3dflipbook.com/wpbakery-addon/"
	],
	"previewMode" => [
		"buy" => "https://checkout.freemius.com/mode/dialog/plugin/15215/plan/25358/",
		"info" => "https://real3dflipbook.com/preview-mode-addon/"
	],
];




function createAddon($name, $title, $description, $urls, $isInstalled = false)
{
	ob_start();

	$infoUrl = isset($urls[$name]['info']) ? $urls[$name]['info'] : '';
	$buyUrl = $urls[$name]['buy'];

?>
	<div class="addons-banner-block-item">
		<div class="addons-banner-block-item-content">
			<h3><?php echo esc_html__($title, 'real3d-flipbook'); ?></h3>
			<p><?php echo esc_html__($description, 'real3d-flipbook'); ?></p>

			<?php if (!empty($infoUrl)) : ?>
				<a class="button button-secondary button-large addons-button" href="<?php echo esc_url($infoUrl) ?>" target="_blank"><?php _e('More Info', 'real3d-flipbook'); ?></a>
			<?php endif; ?>

			<?php if (!$isInstalled) : ?>
				<a class="button button-primary button-large addons-button" href="<?php echo esc_url($buyUrl); ?>" target="_blank"><?php _e('Buy Now', 'real3d-flipbook'); ?></a>
			<?php else : ?>
				<span class="button disabled button-primary button-large addons-button"><?php echo _e('Installed', 'real3d-flipbook'); ?></span>
			<?php endif; ?>
		</div>
	</div>
<?php
	$output = ob_get_clean();
	return $output;
}

?>

<div class='wrap r3d_wrap'>

	<h3><?php _e('Real3D Flipbook Addons', 'real3d-flipbook'); ?></h3>

	<div class="addons">

		<div class="addons-block">

			<p><?php _e('Make Real3D Flipbook more powerful with Addons', 'real3d-flipbook'); ?></p>

			<div class="addons-banner-block-items">

				<?php
				echo createAddon(
					'bundle',
					'Addon Bundle',
					'All 7 add-ons: Book Shelf, PDF Tools, Page Editor, WooCommerce, Elementor, WPBakery, Preview Mode, 57% OFF',
					$urls,
					false
				);

				echo createAddon(
					'pageEditor',
					'Page Editor Addon',
					'Add links, videos, sounds, Youtube, Vimeo and more to flipbook pages easily with visual editor',
					$urls,
					defined('R3D_PAGE_EDITOR_VERSION')
				);

				echo createAddon(
					'wooCommerce',
					'WooCommerce Addon',
					'Display flipbook on WooCommece single product page',
					$urls,
					defined('R3D_WOO_VERSION')
				);

				echo createAddon(
					'pdfTools',
					'PDF Tools Addon',
					'Optimize PDF flipbooks for faster loading by converting PDF to images and JSON',
					$urls,
					defined('R3D_PDF_TOOLS_VERSION')
				);

				echo createAddon(
					'elementor',
					'Elementor Addon',
					'Use Real3D Flipbook with Elementor as an element',
					$urls,
					class_exists("Elementor_Real3D_Flipbook")
				);

				echo createAddon(
					'bookShelf',
					'Bookshelf Addon',
					'Create responsive book shelves with flipbooks',
					$urls,
					class_exists("Bookshelf_Addon")
				);

				echo createAddon(
					'wpBakery',
					'WPBakery Addon',
					'Use Real3D Flipbook with WPBakery page builder',
					$urls,
					class_exists("Real3DFlipbook_VCAddon")
				);

				echo createAddon(
					'previewMode',
					'Preview Mode Addon',
					'Show firsst x number of pages based on user log in status',
					$urls,
					class_exists("R3D_Preview")
				);

				?>

			</div>

		</div>

	</div>

</div>

<?php

wp_enqueue_style('real3d-flipbook-admin');
