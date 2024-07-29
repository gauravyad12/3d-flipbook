<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class='wrap'>

  <h3>Import flipbooks</h3>
	
	
	<p>Import flipbooks from JSON( overwrite existing flipbooks)</p>
			
	<textarea name="flipbooks" id="flipbook-admin-json" rows="20" cols="100" placeholder="Paste JSON here"></textarea>
	<p class="submit"><a href="#" id="import" class="button button-secondary">Import</a></p>


	<h3>Export flipbooks</h3>
	<p>
		<a class='button button-secondary' id="download" href='#'>Download JSON</a>
	</p>

</div>
<?php 

wp_enqueue_script( "real3d-flipbook-import"); 
$r3d_nonce = wp_create_nonce( "r3d_nonce");
wp_localize_script( 'real3d-flipbook-import', 'r3d_nonce', array($r3d_nonce) );