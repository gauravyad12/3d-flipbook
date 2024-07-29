<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$products = $this->products;

function isActive($key, $products) {
    return ($products[$key]['active'] ?? false) && !($products[$key]['fs'] ?? false);
}

function render_license_form($products, $key, $addon) {
    $value = esc_attr($products[$key]['key']);
    $display_activate = empty($value) ? '' : 'display: none;';
    $display_deactivate = !empty($value) ? '' : 'display: none;';
    echo "<form method='post' id='form-{$key}' class='addon-license-form'>";
    echo "<table class='form-table'>";
    echo "<tr valign='top'>
        <th scope='row'>" . __($addon['name'], 'real3d-flipbook') . "</th>
        <td>
            <input type='text' id='{$key}_license' name='{$key}_license' value='{$value}' placeholder='Enter Purchase Code' style='width: 320px;' />
            <input type='button' disabled id='activate-{$key}' class='button button-primary' value='" . __('Activate', 'real3d-flipbook') . "' style='{$display_activate}' />
            <input type='button' id='deactivate-{$key}' class='button button-primary' value='" . __('Deactivate', 'real3d-flipbook') . "' style='{$display_deactivate}' />
        </td>
    </tr>";
    echo "</table>";
    echo "</form>";
}

?>
<div class="wrap">
    <h1><?php _e('License Settings', 'real3d-flipbook'); ?></h1>
    <p><?php _e("This page allows you to activate or deactivate your Real3D Flipbook license and its addons. Please enter your Envato purchase codes to activate your licenses.", 'real3d-flipbook'); ?></p>
    <?php 
    // Render form for the main Real3D Flipbook product
    render_license_form($products, 'r3d', ['name' => 'Real3D Flipbook']);

    $addons_form = false;
    foreach ($products as $key => $addon) {
        if (isActive($key, $products)) {
			if(!$addons_form){
				render_license_form($products, 'addons', ['name' => 'Addons Bundle']);
				$addons_form = true;
			}
            render_license_form($products, $key, $addon);
        }
    }
    ?>
    <div>
        <h2><?php _e('Where to Find Your Envato Purchase Code', 'real3d-flipbook'); ?></h2>
        <ol>
            <li><?php _e('Log in to your Envato Market account.', 'real3d-flipbook'); ?></li>
            <li><?php _e('Hover over your username at the top of the screen.', 'real3d-flipbook'); ?></li>
            <li><?php _e('Click \'Downloads\' from the drop-down menu.', 'real3d-flipbook'); ?></li>
            <li><?php _e('Find the item and click \'License certificate & purchase code\' (available as PDF or text file).', 'real3d-flipbook'); ?></li>
        </ol>
    </div>
</div>
<?php
wp_enqueue_script("real3d-flipbook-license", $this->PLUGIN_DIR_URL."js/license.js", array('jquery'), $this->PLUGIN_VERSION);
$r3d_nonce = wp_create_nonce("r3d_nonce");
wp_localize_script('real3d-flipbook-license', 'r3d_ajax', array('nonce' => $r3d_nonce));
?>
