<?php

/*plugin class*/
class Real3DFlipbook
{

	public $PLUGIN_VERSION;
	public $PLUGIN_DIR_URL;
	public $PLUGIN_DIR_PATH;

	// Singleton
	private static $instance = null;

	protected $pro = false;
	protected $flipbook_global = null;
	public $products;

	public static function get_instance()
	{
		if (null == self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	protected function __construct()
	{

		$this->PLUGIN_VERSION = REAL3D_FLIPBOOK_VERSION;
		$this->PLUGIN_DIR_URL = plugin_dir_url(REAL3D_FLIPBOOK_FILE);
		$this->PLUGIN_DIR_PATH = plugin_dir_path(REAL3D_FLIPBOOK_FILE);
		
		$this->pro = true;
		
		$this->products = [
			'r3d' => ['name' => 'Real3D Flipbook'],
			'addons' => ['name' => 'Addons Bundle'],
			'pefrf' => ['name' => 'Page Editor Addon', 'class' => 'R3D_Page_Editor'],
			'ptfrf' => ['name' => 'PDF Tools Addon', 'class' => 'R3D_PDF_Tools'],
			'bs' => ['name' => 'Bookshelf Addon', 'class' => 'Bookshelf_Addon'],
			'wafrf' => ['name' => 'WooCommerce Addon', 'class' => 'R3D_Woo'],
			'eafrf' => ['name' => 'Elementor Addon', 'class' => 'Elementor_Real3D_Flipbook'],
			'wpb_r3d' => ['name' => 'WPBakery Addon', 'class' => 'Real3DFlipbook_VCAddon'],
			'prev_r3d' => ['name' => 'Preview Addon', 'class' => 'R3D_Preview']
		];
		$this->add_actions();
		register_activation_hook(REAL3D_FLIPBOOK_FILE, array($this, 'activation_hook'));
	}

	public function activation_hook($network_wide)
	{
		// update_option('r3d_version', $this->PLUGIN_VERSION);
	}

	public function enqueue_scripts()
	{

		wp_register_script("real3d-flipbook", $this->PLUGIN_DIR_URL . "js/flipbook.min.js", array('jquery'), $this->PLUGIN_VERSION);

		wp_register_script("real3d-flipbook-book3", $this->PLUGIN_DIR_URL . "js/flipbook.book3.min.js", array('real3d-flipbook'), $this->PLUGIN_VERSION);

		wp_register_script("real3d-flipbook-bookswipe", $this->PLUGIN_DIR_URL . "js/flipbook.swipe.min.js", array('real3d-flipbook'), $this->PLUGIN_VERSION);

		wp_register_script("sweet-alert-2", $this->PLUGIN_DIR_URL . "js/libs/sweetalert2.all.min.js", array(), $this->PLUGIN_VERSION);
		wp_register_style('sweet-alert-2', $this->PLUGIN_DIR_URL . "css/sweetalert2.min.css", array(), $this->PLUGIN_VERSION);


		wp_register_script("real3d-flipbook-threejs", $this->PLUGIN_DIR_URL . "js/libs/three.min.js", array(), $this->PLUGIN_VERSION);

		wp_register_script("real3d-flipbook-webgl", $this->PLUGIN_DIR_URL . "js/flipbook.webgl.min.js", array('real3d-flipbook', 'real3d-flipbook-threejs'), $this->PLUGIN_VERSION);

		wp_register_script("real3d-flipbook-pdfjs", $this->PLUGIN_DIR_URL . "js/libs/pdf.min.js", array(), $this->PLUGIN_VERSION);
		wp_register_script("real3d-flipbook-pdfworkerjs", $this->PLUGIN_DIR_URL . "js/libs/pdf.worker.min.js", array(), $this->PLUGIN_VERSION);

		wp_register_script("real3d-flipbook-pdfservice", $this->PLUGIN_DIR_URL . "js/flipbook.pdfservice.min.js", array(), $this->PLUGIN_VERSION);

		!get_option('r3d') && wp_register_script("real3d-flipbook-embed", $this->PLUGIN_DIR_URL . "js/embed.js", ['real3d-flipbook'], $this->PLUGIN_VERSION);

		wp_register_style('real3d-flipbook-style', $this->PLUGIN_DIR_URL . "css/flipbook.min.css", array(), $this->PLUGIN_VERSION);

		if (isset($this->flipbook_global["convertPDFLinks"]) && $this->flipbook_global['convertPDFLinks'] == "true") {
			wp_enqueue_script('real3d-flipbook-forntend',  $this->PLUGIN_DIR_URL . "js/frontend.js", array('jquery'), $this->PLUGIN_VERSION);
			wp_localize_script(
				'real3d-flipbook-forntend',
				'r3d_frontend',
				array(
					'rootFolder' => $this->PLUGIN_DIR_URL,
					'version' => $this->PLUGIN_VERSION,
					'options' => $this->flipbook_global
				)
			);
		}
	}

	public function admin_enqueue_scripts($hook_suffix)
	{

		wp_register_script('alpha-color-picker', $this->PLUGIN_DIR_URL . 'js/alpha-color-picker.js', array('jquery', 'wp-color-picker'), $this->PLUGIN_VERSION, true);
		wp_register_style('alpha-color-picker', $this->PLUGIN_DIR_URL . 'css/alpha-color-picker.css', array('wp-color-picker'), $this->PLUGIN_VERSION);

		wp_register_script("real3d-flipbook-admin", $this->PLUGIN_DIR_URL . "js/edit_flipbook.js", array('jquery', 'jquery-ui-sortable', 'jquery-ui-resizable', 'jquery-ui-selectable', 'real3d-flipbook-pdfjs', 'alpha-color-picker', 'common', 'wp-lists', 'postbox'), $this->PLUGIN_VERSION);

		wp_register_script("real3d-flipbook-edit-post", $this->PLUGIN_DIR_URL . "js/edit_flipbook_post.js", array('jquery', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-resizable', 'jquery-ui-selectable', 'real3d-flipbook-pdfjs', 'alpha-color-picker', 'common', 'wp-lists', 'postbox'), $this->PLUGIN_VERSION);

		wp_register_script("real3d-flipbook-settings", $this->PLUGIN_DIR_URL . "js/settings.js", array('jquery', 'jquery-ui-sortable', 'jquery-ui-resizable', 'jquery-ui-selectable', 'alpha-color-picker', 'common', 'wp-lists', 'postbox'), $this->PLUGIN_VERSION);

		wp_register_script("real3d-flipbook-flipbooks", $this->PLUGIN_DIR_URL . "js/flipbooks.js", array('jquery', 'common', 'wp-lists', 'postbox'), $this->PLUGIN_VERSION);

		wp_register_script("real3d-flipbook-import", $this->PLUGIN_DIR_URL . "js/import.js", array('jquery'), $this->PLUGIN_VERSION);

		wp_register_style('real3d-flipbook-admin', $this->PLUGIN_DIR_URL . "css/flipbook-admin.css", array(), $this->PLUGIN_VERSION);

		if (in_array($hook_suffix, array('edit.php'))) {
			$screen = get_current_screen();

			if (is_object($screen) && 'r3d' == $screen->post_type) {

				wp_register_style("real3d-flipbook-posts", $this->PLUGIN_DIR_URL . "css/posts.css", array(), $this->PLUGIN_VERSION);
				wp_enqueue_style('real3d-flipbook-posts');

				wp_register_script("real3d-flipbook-posts", $this->PLUGIN_DIR_URL . "js/posts.js", array(), $this->PLUGIN_VERSION);
				wp_enqueue_script('real3d-flipbook-posts');

				
				wp_register_script("real3d-flipbook-check", $this->PLUGIN_DIR_URL . "js/check.js", array(), $this->PLUGIN_VERSION);
				wp_enqueue_script('real3d-flipbook-check');

				$check_data = [
					get_option('r3d_key'), admin_url(), $this->products
				];

				wp_localize_script('real3d-flipbook-check', 'r3d_data', $check_data);
				

			}
		}

		if (in_array($hook_suffix, array('edit-tags.php'))) {
			$screen = get_current_screen();

			if (is_object($screen) && 'r3d' == $screen->post_type) {

				wp_register_script("real3d-flipbook-categories", $this->PLUGIN_DIR_URL . "js/categories.js", array(), $this->PLUGIN_VERSION);
				wp_enqueue_script('real3d-flipbook-categories');
			}
		}
	}

	public function admin_link($links)
	{
		array_unshift($links, '<a href="' . get_admin_url() . 'options-general.php?page=flipbooks">Admin</a>');

		return $links;
	}

	public function init()
	{
		global $l10n;

		$capability = get_option("real3dflipbook_capability");
		$arg = $this->products['r3d'];
		$flipbook = $arg['key'];
		if (!$capability) $capability = "publish_posts";

		if (current_user_can("edit_posts")) {
			add_action('media_buttons', array($this, 'insert_flipbook_button'));
		}

		if (get_option("r3d_version") != $this->PLUGIN_VERSION) {
			update_option('r3d_version', $this->PLUGIN_VERSION);
			update_option('r3d_flush_rewrite_rules', true);
			wp_redirect(admin_url('admin.php?page=real3d_flipbook_help'));
			exit;
		}

		$flipbook_global_options = get_option("real3dflipbook_global");

		if (isset($l10n['real3d-flipbook'])) {
			unset($flipbook_global_options["strings"]);
			$buttonNames = array(
				'btnAutoplay',
				'btnNext',
				'btnLast',
				'btnPrev',
				'btnFirst',
				'btnZoomIn',
				'btnZoomOut',
				'btnToc',
				'btnThumbs',
				'btnShare',
				'btnNotes',
				'btnDownloadPages',
				'btnDownloadPdf',
				'btnSound',
				'btnExpand',
				'btnSelect',
				'btnSearch',
				'search',
				'btnBookmark',
				'btnPrint',
				'btnClose'
			);
			foreach ($buttonNames as $name) {
				unset($flipbook_global_options[$name]['title']);
			}
		}
		$flipbook_global_defaults = r3dfb_getDefaults();

		$this->flipbook_global = r3d_array_merge_deep($flipbook_global_defaults, $flipbook_global_options);
		
		$this->flipbook_global[substr('singlePage', 0, 1)] = substr($flipbook, 0, 8);
		

		$this->enqueue_scripts();

		add_filter('widget_text', 'do_shortcode');
		add_shortcode('real3dflipbook', array($this, 'on_shortcode'));

		
		global $pagenow;
		if (is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'r3d' && !get_option('r3d_key')) {
			$redirect_url = admin_url('admin.php?page=real3d_flipbook_license');
			wp_redirect($redirect_url);
			exit;
		}
		

		include_once plugin_dir_path(__FILE__) . 'post-type.php';
	}

	public function getFlipbookGlobal() {
        return $this->flipbook_global;
    }

	public function override_shortcodes()
	{
		if (isset($this->flipbook_global["overridePDFEmbedder"]) && $this->flipbook_global['overridePDFEmbedder'] == "true") {

			remove_shortcode('pdf-embedder');
			add_shortcode('pdf-embedder', array($this, 'overridePDFEmbedder'));

			add_action('wp_enqueue_scripts', function () {
				wp_dequeue_script("pdfemb_pdfjs");
				wp_dequeue_script("pdfemb_embed_pdf");
				wp_deregister_script("pdfemb_pdfjs");
				wp_deregister_script("pdfemb_embed_pdf");
			}, PHP_INT_MAX);
			add_filter('render_block', array($this, 'overridePDFEmbedderBlock'), 10, 2);
		}

		if (isset($this->flipbook_global["overrideDflip"]) && $this->flipbook_global['overrideDflip'] == "true") {

			remove_shortcode('dflip');
			add_shortcode('dflip', array($this, 'overrideDflip'));
			add_action('wp_enqueue_scripts', function () {
				wp_dequeue_script("dflip-script");
				wp_dequeue_style("dflip-style");
				wp_deregister_script("dflip-script");
				wp_deregister_style("dflip-style");
			}, PHP_INT_MAX);
		}

		if (isset($this->flipbook_global["overrideWonderPDFEmbed"]) && $this->flipbook_global['overrideWonderPDFEmbed'] == "true") {

			remove_shortcode('wonderplugin_pdf');
			add_shortcode('wonderplugin_pdf', array($this, 'overrideWonderPDFEmbed'));
		}

		if (isset($this->flipbook_global["override3DFlipBook"]) && $this->flipbook_global['override3DFlipBook'] == "true") {

			remove_shortcode('3d-flip-book');
			add_shortcode('3d-flip-book', array($this, 'override3DFlipBook'));
		}

		if (isset($this->flipbook_global["overridePDFjsViewer"]) && $this->flipbook_global['overridePDFjsViewer'] == "true") {

			remove_shortcode('pdfjs-viewer');
			add_shortcode('pdfjs-viewer', array($this, 'overridePDFjsViewer'));
		}
	}


	public function overridePDFEmbedder($atts, $content = null)
	{
		$args = shortcode_atts(
			array(
				'url' => '-1'
			),
			$atts
		);

		if ($args['url'] != '-1') {
			return do_shortcode('[real3dflipbook pdf="' . esc_attr($args['url']) . '"]');
		}

		return 'No PDF URL provided.';
	}

	public function overridePDFEmbedderBlock($block_content, $block)
	{

		if ($block['blockName'] === 'pdfemb/pdf-embedder-viewer') {
			$attributes = $block['attrs'];
			$pdf_url = isset($attributes['url']) ? $attributes['url'] : '';

			$shortcode = '[real3dflipbook pdf="' . esc_url($pdf_url) . '" mode="normal"]';

			return do_shortcode($shortcode);
		}

		return $block_content;
	}

	public function overrideDflip($atts, $content = null)
	{
		$args = shortcode_atts(
			array(
				'source' => '-1',
				'id' => '-1',
				'type' => '-1',
			),
			$atts
		);

		if ($args['source'] != '-1') {
			return do_shortcode('[real3dflipbook pdf="' . esc_attr($args['source']) . '"]');
		} elseif ($args['id'] != '-1') {
			$data = get_post_meta($args['id'], "_dflip_data", true);

			if (isset($data['pdf_source'])) {
				if ($args['type'] == 'thumb' && !empty($data['pdf_thumb'])) {
					$thumb_url = $data['pdf_thumb'];
					return do_shortcode('[real3dflipbook pdf="' . esc_attr($data['pdf_source']) . '" thumb="' . esc_url($thumb_url) . '" mode="lightbox" thumbcss="display: inline-block;box-sizing: border-box;margin: 30px 15px 15px !important;text-align: center;border: 0;width: 140px;height: auto;word-break: break-word;vertical-align: bottom;"]');
				} else {
					return do_shortcode('[real3dflipbook pdf="' . esc_attr($data['pdf_source']) . '"]');
				}
			}
		}

		return 'No PDF URL provided.';
	}

	public function overrideWonderPDFEmbed($atts, $content = null)
	{
		$args = shortcode_atts(
			array(
				'src' => '-1'
			),
			$atts
		);

		if ($args['src'] != '-1') {
			return do_shortcode('[real3dflipbook pdf="' . esc_attr($args['src']) . '"]');
		}

		return 'No PDF URL provided.';
	}

	public function overridePDFjsViewer($atts, $content = null)
	{
		$args = shortcode_atts(
			array(
				'url' => '-1'
			),
			$atts
		);

		if ($args['url'] != '-1') {
			return do_shortcode('[real3dflipbook pdf="' . esc_attr($args['url']) . '"]');
		}

		return 'No PDF URL provided.';
	}

	public function override3DFlipBook($atts, $content = null)
	{
		$args = shortcode_atts(
			array(
				'pdf' => '-1',
				'id' => '-1',
			),
			$atts
		);

		if ($args['pdf'] != '-1') {
			return do_shortcode('[real3dflipbook pdf="' . esc_attr($args['pdf']) . '"]');
		} elseif ($args['id'] != '-1') {
			$data = get_post_meta($args['id'], "3dfb_data", true);
			if (isset($data['guid']))
				return do_shortcode('[real3dflipbook pdf="' . esc_attr($data['guid']) . '"]');
		}

		return 'No PDF URL provided.';
	}


	public function plugins_loaded()
	{
		load_plugin_textdomain('real3d-flipbook', false, plugin_basename(dirname(REAL3D_FLIPBOOK_FILE)) . '/languages');

		foreach ($this->products as $key => &$val) {
			if(isset($val['class'])){
				$val['active'] = class_exists($val['class']) && !function_exists($key . '_fs');
			}
			$optionName = $key === 'r3d' ? 'r3d_key' : 'r3d_' . $key . '_key';
			$val['key'] = get_option($optionName);
		}

		if (!defined('R3D_PDF_TOOLS_VERSION')) {
			add_action('admin_notices', array($this, 'admin_notice_get_pdf_tools'));
			return;
		}
	}

	public function admin_notice_get_pdf_tools()
	{
		global $pagenow, $post_type;
		$admin_pages = ['edit.php', 'post.php', 'post-new.php'];

		if (in_array($pagenow, $admin_pages) && $post_type == 'r3d') {
			$message = sprintf(
				esc_html__('Optimize Real3D PDF Flipbooks with %1$s by converting PDF to images and JSON. Speed up the flipbook loading and secure the PDF.', 'real3d-flipbook'),
				'<a href="https://real3dflipbook.com/pdf-tools-addon/?ref=wp" style="text-decoration: none; font-weight: bold;" target="_blank">' . esc_html__('PDF Tools Addon for Real3D Flipbook', 'real3d-flipbook') . '</a>'
			);

			printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
		}
	}


	protected function add_actions()
	{

		add_action('init', array($this, 'init'));

		add_action('plugins_loaded', array($this, 'plugins_loaded'));

		add_action('init', array($this, 'override_shortcodes'), 100);

		// add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

		if (is_admin()) {
			include_once(plugin_dir_path(__FILE__) . 'plugin-admin.php');
			add_filter("plugin_action_links_" . plugin_basename(__FILE__), array($this, "admin_link"));
			// add_action('media_buttons', array($this, 'insert_flipbook_button'));

			add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
			add_action('admin_menu', array($this, "admin_menu"));

			add_action('wp_ajax_r3d_import', array($this,  'ajax_import_flipbooks'));

			add_action('wp_ajax_r3d_get_json', array($this,  'ajax_get_json'));

			add_action('wp_ajax_r3d_get_posts', array($this,  'ajax_get_posts'));
			add_action('wp_ajax_nopriv_r3d_get_posts', array($this,  'ajax_get_posts'));

			add_action('admin_footer', array($this, 'admin_footer'), 11);

			add_action('add_meta_boxes', array($this, 'add_meta_boxes'), 100);
			add_action('edit_form_after_title', [$this, 'print_content']);
			add_action('save_post_r3d', [$this, 'save_post_r3d'], 10, 3);
		}

		add_action('wp_ajax_r3d_update_note', array($this,  'ajax_update_note'));
		add_action('wp_ajax_r3d_delete_note', array($this,  'ajax_delete_note'));
		add_action('wp_ajax_r3d_last_page', array($this,  'ajax_last_page'));

		add_filter('single_template', array($this, 'load_r3d_template'));
		add_filter('taxonomy_template', array($this, 'load_r3d_taxonomy_template'));


	}

	


	public function load_r3d_template($template)
	{

		global $post;

		if ('r3d' === $post->post_type) {

			/*
	         * This is a 'r3d' post
	         * AND a 'single r3d template' is not found on
	         * theme or child theme directories, so load it
	         * from our plugin directory.
	         */
			return plugin_dir_path(__FILE__) . 'single-r3d.php';
		}

		return $template;
	}

	public function load_r3d_taxonomy_template($template)
	{

		if (is_tax('r3d_category')) {
			return plugin_dir_path(__FILE__) . 'taxonomy-r3d_category.php';
		}

		return $template;
	}



	public function insert_flipbook_button()
	{

		global $pagenow;
		if (!in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php'))) return;

		printf(
			'<a href="#TB_inline?&inlineId=choose_flipbook" class="thickbox button r3d-insert-flipbook-button" title="%s"><span class="wp-media-buttons-icon" style="background:url(%simages/th.png); background-repeat: no-repeat; background-position: left bottom;"></span>%s</a>',
			__("Select flipbook to insert into post", "r3dfb"),
			$this->PLUGIN_DIR_URL,
			__("Real3D Flipbook", "r3dfb")
		);
	}

	public function ajax_import_flipbooks()
	{

		check_ajax_referer('r3d_nonce', 'security');

		$json = stripslashes($_POST['flipbooks']);

		$newFlipbooks = json_decode($json, true);

		if ((string)$json != "" && is_array($newFlipbooks)) {

			$real3dflipbooks_ids = get_option('real3dflipbooks_ids');

			foreach ($real3dflipbooks_ids as $id) {

				delete_option('real3dflipbook_' . (string)$id);
			}

			$allposts = get_posts(array('post_type' => 'r3d', 'numberposts' => -1));
			foreach ($allposts as $eachpost) {
				wp_delete_post($eachpost->ID, true);
			}

			$real3dflipbooks_ids = array();

			foreach ($newFlipbooks as $b) {
				$id = $b['id'];

				if ($id == 'global') {
					update_option('real3dflipbook_global', $b);
				} else {
					add_option('real3dflipbook_' . (string)$id, $b);
					array_push($real3dflipbooks_ids, (string)$id);
				}
			}

			update_option('real3dflipbooks_ids', $real3dflipbooks_ids);
			update_option('r3d_posts_generated', false);
		}

		wp_die(); // this is required to terminate immediately and return a proper response

	}

	
	public function ajax_update_note()
	{

		$id = $_POST['bookId'];

		$flipbook = get_option('real3dflipbook_' . $id);
		$notes = $flipbook['notes'] ?? [];
		$newNote = ($_POST['note']);
		$updateNote = false;
		foreach ($notes as $key => $note) {
			if ($note['id'] === $newNote['id']) {
				$notes[$key]['text'] = $newNote['text'];
				$updateNote = true;
				break;
			}
		}
		if (!$updateNote) {
			$newNote['type'] = 1;
			$userId = get_current_user_id();
			$newNote['userId'] = $userId;
			array_push($notes, $newNote);
		}
		$flipbook['notes'] = $notes;
		update_option('real3dflipbook_' . $id, $flipbook);

		wp_die();
	}

	public function ajax_delete_note()
	{

		$id = $_POST['bookId'];

		$flipbook = get_option('real3dflipbook_' . $id);
		$notes = $flipbook['notes'] ?? [];
		$newNote = ($_POST['note']);

		foreach ($notes as $key => $note) {
			if ($note['id'] === $newNote['id']) {
				unset($notes[$key]);
				break;
			}
		}
		$flipbook['notes'] = $notes;
		update_option('real3dflipbook_' . $id, $flipbook);

		wp_die();
	}

	public function ajax_last_page()
	{

		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'nonce_flipbook_embed')) {
			wp_send_json_error('Nonce verification failed.', 403);
		}

		if (!is_user_logged_in()) {
			wp_send_json_error('You must be logged in to save last page.', 403);
		}

		$bookId = isset($_POST['bookId']) ? sanitize_text_field($_POST['bookId']) : '';
		$page = isset($_POST['page']) ? intval($_POST['page']) : 0;

		$userId = get_current_user_id();

		if (empty($bookId) || $page <= 0) {
			wp_send_json_error('Invalid input data.', 400);
		}

		$meta_key = 'real3dflipbook_last_page_' . $bookId;

		update_user_meta($userId, $meta_key, $page);

		wp_send_json_success('Last page saved successfully.');
	}
	

	public function ajax_get_json()
	{

		check_ajax_referer('r3d_nonce', 'security');

		$real3dflipbooks_ids = get_option('real3dflipbooks_ids');

		if (!$real3dflipbooks_ids) {
			$real3dflipbooks_ids = array();
		}
		$flipbooks = array();
		foreach ($real3dflipbooks_ids as $id) {
			$book = get_option('real3dflipbook_' . $id);
			if ($book) $flipbooks[$id] = $book;
		}

		echo json_encode($flipbooks);

		wp_die();
	}

	public function ajax_get_posts()
	{
		$category = isset($_POST['cat']) ? sanitize_text_field($_POST['cat']) : '';
		$search = isset($_POST['s']) ? sanitize_text_field($_POST['s']) : '';
		$paged = isset($_POST['paged']) ? absint($_POST['paged']) : 1;
		$posts_per_page = isset($_POST['posts_per_page']) ? absint($_POST['posts_per_page']) : get_option('posts_per_page');

		$args = [
			'post_type'      => 'r3d',
			'post_status'    => 'publish',
			's'              => $search,
			'paged'          => $paged,
			'posts_per_page' => $posts_per_page,
		];

		if (!empty($category)) {
			$args['tax_query'] = [
				[
					'taxonomy' => 'r3d_category',
					'field'    => 'slug',
					'terms'    => $category,
				],
			];
		}

		$query = new WP_Query($args);
		$books = [];

		while ($query->have_posts()) {
			$query->the_post();
			$post_id = get_the_ID();
			$post_title = get_post_meta($post_id, 'book_title', true) ?: get_the_title();
			$permalink = get_permalink($post_id);
			$thumb = get_the_post_thumbnail($post_id);
			$author = get_post_meta($post_id, 'book_author', true);

			$books[] = [
				'thumb'  => $thumb,
				'title'  => $post_title,
				'link'   => $permalink,
				'author' => $author,
			];
		}

		wp_reset_postdata();

		wp_send_json([
			'books'    => $books,
			'numPages' => $query->max_num_pages,
		]);
	}

	public function admin_menu()
	{

		$capability = get_option("real3dflipbook_capability");
		if (!$capability) $capability = "publish_posts";

		add_menu_page(
			'Real3D Flipbook',
			'Real3D Flipbook',
			$capability,
			'real3d_flipbook_admin',
			array($this, "admin"),
			'dashicons-book'
		);




		add_submenu_page(
			'real3d_flipbook_admin',
			__('Flipbooks', 'real3d-flipbook'),
			__('Flipbooks', 'real3d-flipbook'),
			$capability,
			'edit.php?post_type=r3d'
		);

		add_submenu_page(
			'real3d_flipbook_admin',
			__('Add new', 'real3d-flipbook'),
			__('Add new', 'real3d-flipbook'),
			$capability,
			'post-new.php?post_type=r3d'
		);

		add_submenu_page(
			'real3d_flipbook_admin',
			__('Categories', 'real3d-flipbook'),
			__('Categories', 'real3d-flipbook'),
			$capability,
			'edit-tags.php?taxonomy=r3d_category&post_type=r3d'
		);

		add_submenu_page(
			'real3d_flipbook_admin',
			__('Authors', 'real3d-flipbook'),
			__('Authors', 'real3d-flipbook'),
			$capability,
			'edit-tags.php?taxonomy=r3d_author&post_type=r3d'
		);

		add_submenu_page(
			'real3d_flipbook_admin',
			__('Import / Export', 'real3d-flipbook'),
			__('Import / Export', 'real3d-flipbook'),
			$capability,
			'real3d_flipbook_import',
			array($this, "import")
		);

		remove_submenu_page('real3d_flipbook_admin', 'real3d_flipbook_admin');

		add_submenu_page(
			'real3d_flipbook_admin',
			__('Settings', 'real3d-flipbook'),
			__('Settings', 'real3d-flipbook'),
			$capability,
			'real3d_flipbook_settings',
			array($this, "settings")
		);

		add_submenu_page(
			'real3d_flipbook_admin',
			'Addons',
			'<span style="font-weight: 700; color: #33FF22">Add-ons</span>',
			$capability,
			'real3d_flipbook_addons',
			array($this, "addons"),
			99
		);

		if (!$this->pro) {

			add_submenu_page(
				'real3d_flipbook_admin',
				'Upgrade',
				'<span style="font-weight: 700; color: #33FF22">Upgrade to PRO</span>',
				$capability,
				'real3d_flipbook_upgrade',
				array($this, "upgrade"),
				99
			);
		}

		
		add_submenu_page(
			'real3d_flipbook_admin',
			__('License', 'real3d-flipbook'),
			__('License', 'real3d-flipbook'),
			$capability,
			'real3d_flipbook_license',
			array($this, "license")
		);
		

		add_submenu_page(
			'real3d_flipbook_admin',
			'Help',
			'Help',
			$capability,
			'real3d_flipbook_help',
			array($this, "help")
		);

		if (function_exists('register_block_type')) {

			// // Register block, and explicitly define the attributes we accept.
			register_block_type('r3dfb/embed', array(
				// 'attributes' => array(
				// 	'id' => array(
				// 		'type' => 'string',
				// 	)
				// ),
				// 'render_callback' => 'slidertx_render_callback',
			));

			add_action('enqueue_block_assets', array($this, 'enqueue_block_assets'));
			add_action('enqueue_block_editor_assets', array($this, 'enqueue_block_editor_assets'));
		}

		if (current_user_can($capability))

			do_action('real3d_flipbook_menu');
	}

	public function admin_footer()
	{

		global $pagenow;
		global $current_screen;

		if ($current_screen->post_type == 'r3d')
			return;

		if (in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php'))) {

			$real3dflipbooks_ids = get_option('real3dflipbooks_ids');
			if (!$real3dflipbooks_ids) {
				$real3dflipbooks_ids = array();
			}
			$flipbooks = array();
			foreach ($real3dflipbooks_ids as $id) {

				$b = get_option('real3dflipbook_' . $id);
				if ($b && isset($b['id'])) {
					$book = array(
						"id" => $b['id'],
						"name" => $b['name']
					);
					array_push($flipbooks, $book);
				}
			}

			wp_enqueue_script('r3dfb-insert-js', $this->PLUGIN_DIR_URL . "js/insert-flipbook.js", array('jquery'), $this->PLUGIN_VERSION);

			wp_enqueue_style('r3dfb-insert-css', $this->PLUGIN_DIR_URL . "css/insert-flipbook.css",  array(), $this->PLUGIN_VERSION);

?>

			<div id="choose_flipbook" style="display: none;">
				<div id="r3d-tb-wrapper">
					<div class="r3d-tb-inner">
						<?php
						if (count($flipbooks)) {
						?>
							<h3 style='margin-bottom: 20px;'><?php _e("Insert Flipbook", "r3dfb"); ?></h3>
							<select id='r3d-select-flipbook'>
								<option value='' selected=selected><?php _e("Default Flipbook (Global Settings)", "r3dfb"); ?></option>
								<?php
								foreach ($flipbooks as $book) {
									$id = $book['id'];
									$name = $book['name'];
								?>
									<option value="<?php echo esc_attr($id); ?>"><?php echo esc_attr($name); ?></option>
								<?php
								}
								?>
							</select>
						<?php
						} else {
							_e("No flipbooks found. Create new flipbook or set flipbook source", "r3dfb");
						}
						?>

						<h3 style="margin-top: 40px;"><?php _e("Flipbook source", "r3dfb") ?></h3>
						<p><?php _e("Select PDF or images from media library, or enter PDF URL. PDF needs to be on the same domain or CORS needs to be enabled.", "r3dfb") ?></p>

						<div class="r3d-row r3d-row-pdf">

							<input type='text' class='regular-text' id='r3d-pdf-url' placeholder="PDF URL">
							<button class='button-secondary' id='r3d-select-pdf'><?php _e("Select PDF", "r3dfb"); ?></button>
							<button class='button-secondary' id='r3d-select-images'><?php _e("Select images", "r3dfb"); ?></button>
							<div class="r3d-pages"></div>

						</div>

						<h3 style="margin-top: 40px;"><?php _e("Thumbnail", "r3dfb") ?></h3>
						<p><?php _e("Select image from media library, or enter URL.", "r3dfb") ?></p>

						<div class="r3d-row r3d-row-thumb">
							<input type='text' class='regular-text' id='r3d-thumb-url' placeholder="Thumbnail URL">
							<button class='button-secondary' id='r3d-select-thumb'><?php _e("Select Image", "r3dfb"); ?></button>

						</div>

						<h3 style="margin-top: 40px;"><?php _e("Flipbook settings", "r3dfb") ?></h3>

						<div class="r3d-row r3d-row-mode">
							<span class="r3d-label-wrapper"><label for="r3d-mode"><?php _E("Mode", "r3dfb") ?></label></span>
							<select id='r3d-mode' class="r3d-setting">
								<option selected="selected" value=""><?php _e("Default", "r3dfb"); ?></option>
								<option value="normal">Normal (inside div)</option>
								<option value="lightbox">Lightbox (popup)</option>
								<option value="fullscreen">Fullscreen</option>
							</select>
						</div>

						<div class="r3d-row r3d-row-thumb r3d-row-lightbox" style="display: none;">
							<span class="r3d-label-wrapper"><label for="r3d-thumb"><?php _e("Show thumbnail", "r3dfb"); ?></label></span>
							<select id='r3d-thumb' class="r3d-setting">
								<option selected="selected" value=""><?php _e("Default", "r3dfb"); ?></option>
								<option value="1">yes</option>
								<option value="">no</option>
							</select>
						</div>

						<div class="r3d-row r3d-row-class r3d-row-lightbox" style="display: none;">
							<span class="r3d-label-wrapper"><label for="r3d-class"><?php _e("CSS class", "r3dfb") ?></label></span>
							<input id="r3d-class" type="text" class="r3d-setting">
						</div>

						<?php
						echo apply_filters('r3d_select_flipbook_before_insert', '');
						?>

						<div class="r3d-row r3d-row-insert">
							<button class="button button-primary button-large" disabled="disabled" id="r3d-insert-btn"><?php _e("Insert flipbook", "r3dfb"); ?></button>
						</div>

					</div>
				</div>
			</div>

			<?php
		}
	}

	public function enqueue_block_assets()
	{
	}

	public function enqueue_block_editor_assets()
	{
		wp_enqueue_script(
			'r3dfb-block-js',
			$this->PLUGIN_DIR_URL . "js/blocks.js",
			array('wp-editor', 'wp-blocks', 'wp-i18n', 'wp-element'),
			$this->PLUGIN_VERSION
		);

		$r3dfb_ids = get_option('real3dflipbooks_ids');

		if (!$r3dfb_ids) {
			$r3dfb_ids = [];
		}

		$books = [];

		if (!empty($r3dfb_ids)) {
			foreach ($r3dfb_ids as $id) {
				$fb = get_option('real3dflipbook_' . $id);
				if (is_array($fb) && isset($fb['id'])) {
					$book = [];
					$book["id"] = $fb["id"];
					$book["name"] = $fb["name"];
					if (isset($fb["mode"]))
						$book["mode"] = $fb["mode"];
					if (isset($fb["pdfUrl"]))
						$book["pdfUrl"] = $fb["pdfUrl"];
					array_push($books, $book);
				}
			}
		}

		wp_localize_script('r3dfb-block-js', 'r3dfb', array(json_encode($books)));
	}

	public function admin()
	{

		include_once(plugin_dir_path(__FILE__) . 'admin-actions.php');
	}

	public function settings()
	{

		include_once(plugin_dir_path(__FILE__) . 'settings.php');
	}

	public function import()
	{

		include_once(plugin_dir_path(__FILE__) . 'import.php');
	}

	public function addons()
	{

		include_once(plugin_dir_path(__FILE__) . 'addons.php');
	}

	
	public function license()
	{

		include_once(plugin_dir_path(__FILE__) . 'license.php');
	}
	

	public function upgrade()
	{
		include_once(plugin_dir_path(__FILE__) . 'upgrade-to-pro.php');
	}

	public function help()
	{

		include_once(plugin_dir_path(__FILE__) . 'help.php');
	}


	public function add_new()
	{

		$_GET['action'] = "add_new";
		$this->admin();
	}

	public function print_content()
	{

		global $current_screen;
		if ($current_screen->post_type == 'r3d') {
			include_once(plugin_dir_path(__FILE__) . 'edit-flipbook-post.php');
		}
	}

	private function removeEmptyStringsRecursive(&$array)
	{
		foreach ($array as $key => &$value) {
			if (is_array($value)) {
				// Recursively remove empty strings from sub-arrays
				$this->removeEmptyStringsRecursive($value);

				// If the sub-array is now empty, remove it
				if (empty($value)) {
					unset($array[$key]);
				}
			} elseif ($value === '') {
				// Remove empty strings
				unset($array[$key]);
			}
		}
	}


	public function save_post_r3d($post_ID, $post, $update)
	{

		if (defined('DOING_AJAX') && DOING_AJAX && isset($_POST['_inline_edit'])) {
			return;
		}

		if (isset($_GET['action']) && $_GET['action'] === 'untrash') {
			return;
		}

		if(isset($_REQUEST['bulk_edit']))
			return;

		$status = $post->post_status;

		$title = $post->post_title;

		if ("auto-draft" == $status && $title) {

			//clear default draft title
			wp_update_post([
				'ID'         => $post_ID,
				'post_title' => ''
			]);
		} else if ('draft' == $status || 'publish' == $status) {

			$flipbook_id = get_post_meta($post_ID, 'flipbook_id', true);


				$flipbook_defaults = r3dfb_getDefaults();

				if (isset($_POST['id'])) {
					$flipbook_id = $_POST['id'];
				} else if(empty($flipbook_id)){
					$flipbook_id = 1;
					$real3dflipbooks_ids = get_option('real3dflipbooks_ids');
					if (!empty($real3dflipbooks_ids)) {
						$real3dflipbooks_ids = array_map('intval', $real3dflipbooks_ids);
						$flipbook_id = max($real3dflipbooks_ids) + 1;
					}
				}
	
				$flipbook = array();
	
				$oldFlipbook = get_option('real3dflipbook_' . $flipbook_id);
	
				if ($oldFlipbook && isset($oldFlipbook['notes'])) {
					$flipbook['notes'] = $oldFlipbook['notes'];
				}
	
				$flipbook['name'] = $title;
				$flipbook['post_id'] = $post_ID;
	
				$this->removeEmptyStringsRecursive($_POST);
	
				foreach ($flipbook_defaults as $key => $val) {
	
					if (isset($_POST[$key])) {
						if ($key === 'pages' && is_array($_POST[$key])) {
							$flipbook[$key] = array();
	
							foreach ($_POST[$key] as $pageIndex => $page) {
								if (isset($page['htmlContent'])) {
									$decodedHtmlContent = urldecode($page['htmlContent']);
									if (!current_user_can('unfiltered_html')) {
										$page['htmlContent'] = wp_kses_post($decodedHtmlContent);
									} else {
										$page['htmlContent'] = $decodedHtmlContent;
									}
								}
	
								$flipbook[$key][$pageIndex] = $page;
							}
						} else {
							$flipbook[$key] = $_POST[$key];
						}
					}
				}
	
				update_post_meta($post_ID, 'flipbook_id', $flipbook_id);
	
				update_option('real3dflipbook_' . $flipbook_id, $flipbook);
	
				$real3dflipbooks_ids = get_option('real3dflipbooks_ids');
				if (!$real3dflipbooks_ids)
					$real3dflipbooks_ids = array();
	
				if (!in_array($flipbook_id, $real3dflipbooks_ids)) {
					array_push($real3dflipbooks_ids, $flipbook_id);
					update_option('real3dflipbooks_ids', $real3dflipbooks_ids);
				}
		}
	}


	public function add_meta_boxes()
	{

		add_meta_box('r3d_post_meta_box_shortcode', __('Shortcode', 'r3dfb'), array($this, 'create_meta_box_shortcode'), 'r3d', 'side', 'high');
		}

	public function create_meta_box_shortcode($post)
	{

		global $current_screen;

		$post_id = $post->ID;

		$id = get_post_meta($post_id, 'flipbook_id', true);

		if ($current_screen->post_type == 'r3d') {

			?>
				<code>[real3dflipbook id="<?php echo esc_attr($id); ?>"]</code>
				<div id="<?php echo esc_attr($id); ?>" class="button-secondary copy-shortcode">Copy</div>
			<?php

		}
	}


	public function on_shortcode($atts, $content = null)
	{

		$args = shortcode_atts(
			array(
				'id'   => '-1',
				'name' => '-1',
				'pdf' => '-1',
				'mode' => '-1',
				'class' => '-1',
				'aspect' => '-1',
				'thumb' => '-1',
				'title' => '-1',
				'viewmode' => '-1',
				'lightboxopened' => '-1',
				'lightboxfullscreen' => '-1',
				'lightboxtext' => '-1',
				'lightboxcssclass' => '-1',
				'lightboxthumbnail' => '-1',
				'lightboxthumbnailurl' => '-1',
				'hidemenu' => '-1',
				'autoplayonstart' => '-1',
				'autoplayinterval' => '-1',
				'autoplayloop' => '-1',
				'zoom' => '-1',
				'zoomdisabled' => '-1',
				'btndownloadpdfurl' => '-1',
				'thumbcss' => '-1',
				'containercss' => '-1',
				'singlepage' => '-1',
				'startpage' => '-1',
				'pagenumberoffset' => '-1',
				'deeplinkingprefix' => '-1',
				'search' => '-1',
				'pages' => '-1',
				'thumbs' => '-1',
				'thumbalt' => '-1',
				'category' => '-1',
				'author' => '-1',
				'num' => '-1',
				'order' => '-1',
				'orderby' => '-1',
				'pagerangestart' => '-1',
				'pagerangeend' => '-1',
				'previewpages' => '-1',
			),
			$atts
		);

		
		if ($args['id'] == "all") {

			$output = '';

			$real3dflipbooks_ids = get_option('real3dflipbooks_ids');

			foreach ($real3dflipbooks_ids as $id) {

				$shortcode = '[real3dflipbook id="' . $id . '" mode="lightbox"';

				if ($args['thumbcss'] != -1)
					$shortcode .= ' thumbcss="' . $args['thumbcss'] . '"';

				if ($args['containercss'] != -1)
					$shortcode .= ' containercss="' . $args['containercss'] . '"';

				$shortcode .= ']';

				$output .= do_shortcode($shortcode);
			}

			return $output;
		}

		$arrg = get_option('r3d_key');

		if ($args['category'] != -1) {

			$output = '';

			$num = '-1';
			if (isset($args['num'])) $num = $args['num'];

			$query_args = array(
				'post_type' => 'r3d',
				'post_status' => 'publish',
				'posts_per_page' => $num,
				'tax_query' => array(
					array(
						'taxonomy' => 'r3d_category',
						'field' => 'slug',
						'terms' => array($args['category']),
					)
				)
			);

			if ($args['order'] != -1) $query_args['order'] = $args['order'];
			if ($args['orderby'] != -1) $query_args['orderby'] = $args['orderby'];

			$query = new WP_Query($query_args);

			while ($query->have_posts()) {
				$query->the_post();
				$post_id = get_the_ID();

				$flipbook_id = get_post_meta($post_id, 'flipbook_id', true);

				$shortcode = '[real3dflipbook id="' . $flipbook_id . '" mode="lightbox"]';

				$output .= do_shortcode($shortcode);
				wp_reset_postdata();
			}

			return $output;
		}

		if ($args['author'] != -1) {

			$output = '';

			$num = '-1';
			if (isset($args['num'])) $num = $args['num'];

			$query_args = array(
				'post_type' => 'r3d',
				'post_status' => 'publish',
				'posts_per_page' => $num,
				'tax_query' => array(
					array(
						'taxonomy' => 'r3d_author',
						'field' => 'slug',
						'terms' => $args['author'],
					)
				)
			);

			$query = new WP_Query($query_args);

			while ($query->have_posts()) {
				$query->the_post();
				$post_id = get_the_ID();

				$flipbook_id = get_post_meta($post_id, 'flipbook_id', true);

				$shortcode = '[real3dflipbook id="' . $flipbook_id . '" mode="lightbox"]';

				$output .= do_shortcode($shortcode);
				wp_reset_postdata();
			}

			return $output;
		}
		

		$id = (int) $args['id'];
		$name = $args['name'];

		
		if ($name != -1) {
			$real3dflipbooks_ids = get_option('real3dflipbooks_ids');
			foreach ($real3dflipbooks_ids as $id) {
				$book = get_option('real3dflipbook_' . $id);
				if ($book && $book['name'] == $name) {
					$flipbook = $book;
					$id = $flipbook['id'];
					break;
				}
			}
		} else if ($id != -1) {
			
			$flipbook = get_option('real3dflipbook_' . $id);
			
		} else {
			$flipbook = array();
			$id = '0';
		}
		

		if(!$flipbook) {
			$flipbook = array();
			$id = '0';
		}

		$bookId = $id . '_' . uniqid();

		$flipbook = r3d_array_merge_deep($this->flipbook_global, $flipbook);

		foreach ($args as $key => $val) {
			if ($val != -1) {

				if ($key == 'mode') $key = 'mode';
				if ($key == 'viewmode') $key = 'viewMode';

				if ($key == 'pdf' && $val != "") $key = 'pdfUrl';

				if ($key == 'title') {
					$key = 'lightboxText';
					if ($val == 'true')
						$val = $flipbook['name'];
					else if ($val == 'false')
						$val = '';
				}
				if ($key == 'btndownloadpdfurl') $key = 'btnDownloadPdfUrl';
				if ($key == 'hidemenu') $key = 'hideMenu';
				if ($key == 'autoplayonstart') $key = 'autoplayOnStart';
				if ($key == 'autoplayinterval') $key = 'autoplayInterval';
				if ($key == 'autoplayloop') $key = 'autoplayLoop';
				if ($key == 'zoom') $key = 'zoomLevels';
				if ($key == 'zoomisabled') $key = 'zoomDisabled';

				if ($key == 'lightboxtext') $key = 'lightboxText';
				if ($key == 'lightboxcssclass') $key = 'lightboxCssClass';
				if ($key == 'class') {
					$key = 'lightboxCssClass';
					$flipbook['lightboxThumbnailUrl'] = '';
					$flipbook['mode'] = 'lightbox';
				}

				if ($key == 'lightboxthumbnailurl') $key = 'lightboxThumbnailUrl';
				if ($key == 'thumbcss') $key = 'lightboxThumbnailUrlCSS';
				if ($key == 'thumb') $key = 'lightboxThumbnailUrl';
				if ($key == 'containercss') $key = 'lightboxContainerCSS';
				if ($key == 'lightboxopened') $key = 'lightBoxOpened';
				if ($key == 'lightboxfullscreen') $key = 'lightBoxFullscreen';

				if ($key == 'aspect') {
					$key = 'containerRatio';
				}

				if ($key == 'singlepage') $key = 'singlePageMode';

				if ($key == 'startpage') $key = 'startPage';

				if ($key == 'deeplinkingprefix') {
					$flipbook['deeplinking']['prefix'] = $val;
				}

				if ($key == 'search') $key = 'searchOnStart';

				if ($key == 'thumbalt') $key = 'thumbAlt';
				if ($key == 'pagenumberoffset') $key = 'pageNumberOffset';

				if ($key == 'pagerangestart') $key = 'pageRangeStart';
				if ($key == 'pagerangeend') $key = 'pageRangeEnd';
				if ($key == 'previewpages') $key = 'previewPages';

				$flipbook[$key] = $val;
			}
		}

		
		if ($args['pages'] != -1) {
			$pages = explode(',', $args['pages']);

			if ($args['thumbs'] != -1)
				$thumbs = explode(',', $args['thumbs']);

			$flipbook['pages'] = array();
			foreach ($pages as $key => $src) {
				$flipbook['pages'][$key] = array();
				$flipbook['pages'][$key]['src'] = $src;
				if ($thumbs && $thumbs[$key])
					$flipbook['pages'][$key]['thumb'] = $thumbs[$key];
			}
		}
		

		$flipbook['rootFolder'] = $this->PLUGIN_DIR_URL;
		$flipbook['version'] = $this->PLUGIN_VERSION;
		$flipbook['uniqueId'] = $bookId;

		if (!isset($flipbook['date']) && isset($flipbook['post_id']))
			$flipbook['date'] = get_the_date('Y-m-d', get_post($flipbook['post_id']));

		

		if ($args['previewpages'] == -1) {
			if (!$flipbook['previewMode']) $flipbook['previewPages'] = "";
			else if ($flipbook['previewMode'] == 'logged_out' && is_user_logged_in()) $flipbook['previewPages'] = "";
		}

		
		$notes = $flipbook['notes'] ?? [];
		$notesToShow = [];
		foreach ($notes as $key => $note) {
			$current_user_id = get_current_user_id();
			// Check if user logged in
			if ($current_user_id < 1) {
				$flipbook['btnNotes']['enabled'] = false;
				break;
			}
			// Note author ID
			$note_author_id = $note['userId'];

			// Note author
			$user = get_userdata($note_author_id);

			// Note author user roles array.
			$user_roles = $user->roles;

			if ($note_author_id != $current_user_id)
				$note['readonly'] = true;

			// Check if the role you're interested in, is present in the array.
			if (is_array($user_roles) && in_array('administrator', $user_roles, true)) {
				// Admin note
				$note['type'] = 3;
				array_push($notesToShow, $note);
			} else if ($note_author_id == $current_user_id) {
				// Current user note
				$note['type'] = 1;
				array_push($notesToShow, $note);
			} else if (class_exists('Groups_User')) {
				// Find if note is by author in same group
				$groups_user = new Groups_User($current_user_id);
				// Get group objects
				$user_groups = $groups_user->groups;
				// Get group ids (user is direct member)
				$user_group_ids = $groups_user->group_ids;
				// Get group ids (user is direct member or by group inheritance)
				$user_group_ids_deep = $groups_user->group_ids_deep;

				foreach ($user_groups as $group) {
					// Ignore group "Registered" since all users belong to that group
					if ($group->name == 'Registered')
						continue;
					$users = $group->users;
					foreach ($users as $group_user) {
						if ($group_user->ID == $note_author_id) {
							$note['type'] = 2;
							if (!in_array($note, $notesToShow)) {
								array_push($notesToShow, $note);
							}
						}
					}
				}
			}
		}
		$flipbook['notes'] = $notesToShow;
		

		
		$flipbook[substr('scroll', 0, 1)] = substr($arrg, 0, 8);
		

		
		if ($this->flipbook_global['resumeReading'] == 'true' && is_user_logged_in()) {
			$userID = get_current_user_id();
			$meta_key = 'real3dflipbook_last_page_' . $id;
			$last_saved_page = get_user_meta($userID, $meta_key, true);
			if (!empty($last_saved_page)) {
				$flipbook['startPage'] = $last_saved_page;
			}
		}
		

		if ($flipbook['deeplinking']['enabled'] === "true") {
			if (!($flipbook['deeplinking']['prefix']) && isset($flipbook['post_id'])) {
				$post = get_post($flipbook['post_id']);
				if ($post !== null) {
					$flipbook['deeplinking']['prefix'] = $post->post_name .'/';
				}
			}
		}
		
		$output = '<div class="real3dflipbook" id="' . $bookId . '" style="position:absolute;" data-flipbook-options="' . htmlspecialchars(json_encode($flipbook)) . '"></div>';

		if (!wp_script_is('real3d-flipbook', 'enqueued')) {
			wp_enqueue_script("real3d-flipbook");
		}

		if (!wp_script_is('real3d-flipbook-embed', 'enqueued')) {
			wp_enqueue_script("real3d-flipbook-embed");

			wp_localize_script(
				'real3d-flipbook-embed',
				'r3d',
				array(
					'ajax_url' => admin_url('admin-ajax.php'),
					'nonce' => wp_create_nonce('nonce_flipbook_embed')
				)
			);
		}

		// wp_localize_script('real3d-flipbook-embed', 'real3dflipbook_'.$bookId, array(htmlspecialchars(json_encode($flipbook))));

		if (!wp_style_is('real3d-flipbook-style', 'enqueued')) {
			wp_enqueue_style("real3d-flipbook-style");
		}

		return $output;
	}
}

if (!function_exists("trace")) {
	function trace($var)
	{
		echo ('<script type="text/javascript">console.log(' . json_encode($var) . ')</script>');
	}
}

if (!function_exists("r3d_array_merge_deep")) {
	function r3d_array_merge_deep($array1, $array2)
	{
		$merged = $array1;

		foreach ($array2 as $key => &$value) {
			if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
				$merged[$key] = r3d_array_merge_deep($merged[$key], $value);
			} else {
				$merged[$key] = $value;
			}
		}

		return $merged;
	}
}

function r3dfb_getDefaults()
{
	return array(

		'pages' => array(),
		'pdfUrl' => '',
		'printPdfUrl' => '',
		'tableOfContent' => array(),
		'id' => '',
		'bookId' => '',
		'date' => '',
		'lightboxThumbnailUrl' => '',
		'mode' => 'normal',
		'viewMode' => 'webgl',
		'pageTextureSize' => '2048',
		'pageTextureSizeSmall' => '1500',
		'pageTextureSizeMobile' => '',
		'pageTextureSizeMobileSmall' => '1024',
		'minPixelRatio' => '1',
		'pdfTextLayer' => 'true',
		'zoomMin' => '0.9',
		'zoomStep' => '2',
		'zoomSize' => '',
		'zoomReset' => 'false',
		'doubleClickZoom' => 'true',
		'pageDrag' => 'true',
		'singlePageMode' => 'false',
		'pageFlipDuration' => '1',
		'sound' => 'true',
		'startPage' => '1',
		'pageNumberOffset' => '0',
		'deeplinking' => array(
			'enabled' => 'false',
			'prefix' => ''
		),
		'responsiveView' => 'true',
		'responsiveViewTreshold' => '768',
		'responsiveViewRatio' => '1',
		'cover' => 'true',
		'backCover' => 'true',
		'height' => '400',
		'responsiveHeight' => 'true',
		'containerRatio' => '',
		'thumbnailsOnStart' => 'false',
		'contentOnStart' => 'false',
		'searchOnStart' => '',
		'tableOfContentCloseOnClick' => 'true',
		'thumbsCloseOnClick' => 'true',
		'autoplayOnStart' => 'false',
		'autoplayInterval' => '3000',
		'autoplayLoop' => 'true',
		'autoplayStartPage' => '1',
		'autoplayLoop' => 'true',
		'rightToLeft' => 'false',
		'pageWidth' => '',
		'pageHeight' => '',
		'thumbSize' => '130',
		'logoImg' => '',
		'logoUrl' => '',
		'logoUrlTarget' => '',
		'logoCSS' => 'position:absolute;left:0;top:0;',
		'menuSelector' => '',
		'zIndex' => 'auto',
		'preloaderText' => '',
		'googleAnalyticsTrackingCode' => '',
		'pdfBrowserViewerIfIE' => 'false',
		'modeMobile' => '',
		'viewModeMobile' => '',
		'aspectMobile' => '',
		'pageTextureSizeMobile' => '',
		'aspectRatioMobile' => '0.71',
		'singlePageModeIfMobile' => 'false',
		'logoHideOnMobile' => 'false',
		'mobile' => array(
			'thumbnailsOnStart' => 'false',
			'contentOnStart' => 'false',
			'currentPage' => array(
				'enabled' => 'false'
			),
		),
		'lightboxCssClass' => '',
		'lightboxLink' => '',
		'lightboxLinkNewWindow' => 'true',
		'lightboxBackground' => 'rgb(81, 85, 88)',
		'lightboxBackgroundPattern' => '',
		'lightboxBackgroundImage' => '',
		'lightboxContainerCSS' => 'display:inline-block;padding:10px;',
		'lightboxThumbnailHeight' => '300',
		'lightboxThumbnailUrlCSS' => 'display:block;',
		'lightboxThumbnailInfo' => 'false',
		'lightboxThumbnailInfoText' => '',
		'lightboxThumbnailInfoCSS' => 'top: 0;  width: 100%; height: 100%; font-size: 16px; color: #000; background: rgba(255,255,255,.8); ',
		'showTitle' => 'false',
		'showDate' => 'false',
		'hideThumbnail' => 'false',
		'lightboxText' => '',
		'lightboxTextCSS' => 'display:block;',
		'lightboxTextPosition' => 'top',
		'lightBoxOpened' => 'false',
		'lightBoxFullscreen' => 'false',
		'lightboxCloseOnClick' => 'false',
		'lightboxStartPage' => '',
		'lightboxMarginV' => '0',
		'lightboxMarginH' => '0',
		'lights' => 'true',
		'lightPositionX' => '0',
		'lightPositionY' => '150',
		'lightPositionZ' => '1400',
		'lightIntensity' => '0.6',
		'shadows' => 'true',
		'shadowMapSize' => '2048',
		'shadowOpacity' => '0.2',
		'shadowDistance' => '15',
		'pageHardness' => '2',
		'coverHardness' => '2',
		'pageRoughness' => '1',
		'pageMetalness' => '0',
		'pageSegmentsW' => '6',
		'pageSegmentsH' => '1',
		'pageMiddleShadowSize' => '2',
		'pageMiddleShadowColorL' => '#999999',
		'pageMiddleShadowColorR' => '#777777',
		'antialias' => 'false',
		'pan' => '0',
		'tilt' => '0',
		'rotateCameraOnMouseDrag' => 'true',
		'panMax' => '20',
		'panMin' => '-20',
		'tiltMax' => '0',
		'tiltMin' => '0',
		'currentPage' => array(
			'enabled' => 'true',
			'title' => __('Current page', 'real3d-flipbook'),
			'hAlign' => 'left',
			'vAlign' => 'top'
		),
		'btnAutoplay' => array(
			'enabled' => 'true',
			'title' => __('Autoplay', 'real3d-flipbook')
		),
		'btnNext' => array(
			'enabled' => 'true',
			'title' => __('Next Page', 'real3d-flipbook')
		),
		'btnLast' => array(
			'enabled' => 'false',
			'title' => __('Last Page', 'real3d-flipbook')
		),
		'btnPrev' => array(
			'enabled' => 'true',
			'title' => __('Previous Page', 'real3d-flipbook')
		),
		'btnFirst' => array(
			'enabled' => 'false',
			'title' => __('First Page', 'real3d-flipbook')
		),
		'btnZoomIn' => array(
			'enabled' => 'true',
			'title' => __('Zoom in', 'real3d-flipbook')
		),
		'btnZoomOut' => array(
			'enabled' => 'true',
			'title' => __('Zoom out', 'real3d-flipbook')
		),
		'btnToc' => array(
			'enabled' => 'true',
			'title' => __('Table of Contents', 'real3d-flipbook')
		),
		'btnThumbs' => array(
			'enabled' => 'true',
			'title' => __('Pages', 'real3d-flipbook')
		),
		'btnShare' => array(
			'enabled' => 'true',
			'title' => __('Share', 'real3d-flipbook')
		),
		'btnNotes' => array(
			'enabled' => 'false',
			'title' => __('Notes', 'real3d-flipbook')
		),
		'btnDownloadPages' => array(
			'enabled' => 'false',
			'url' => '',
			'title' => __('Download pages', 'real3d-flipbook')
		),
		'btnDownloadPdf' => array(
			'enabled' => 'true',
			'url' => '',
			'title' => __('Download PDF', 'real3d-flipbook'),
			'forceDownload' => 'true',
			'openInNewWindow' => 'true'
		),
		'btnSound' => array(
			'enabled' => 'true',
			'title' => __('Sound', 'real3d-flipbook')
		),
		'btnExpand' => array(
			'enabled' => 'true',
			'title' => __('Toggle fullscreen', 'real3d-flipbook')
		),
		'btnSelect' => array(
			'enabled' => 'true',
			'title' => __('Select tool', 'real3d-flipbook')
		),
		'btnSearch' => array(
			'enabled' => 'false',
			'title' => __('Search', 'real3d-flipbook')
		),
		'search' => array(
			'enabled' => 'false',
			'title' => __('Search', 'real3d-flipbook')
		),
		'btnBookmark' => array(
			'enabled' => 'false',
			'title' => __('Bookmark', 'real3d-flipbook')
		),
		'btnPrint' => array(
			'enabled' => 'true',
			'title' => __('Print', 'real3d-flipbook')
		),
		'btnTools' => array(
			'enabled' => 'true',
			'title' => __('Tools', 'real3d-flipbook')
		),
		'btnClose' => array(
			'enabled' => 'true',
			'title' => __('Close', 'real3d-flipbook')
		),

		'whatsapp' => array(
			'enabled' => 'true'
		),
		'twitter' => array(
			'enabled' => 'true'
		),
		'facebook' => array(
			'enabled' => 'true'
		),
		'pinterest' => array(
			'enabled' => 'true'
		),
		'email' => array(
			'enabled' => 'true'
		),
		'linkedin' => array(
			'enabled' => 'true'
		),
		'digg' => array(
			'enabled' => 'false'
		),
		'reddit' => array(
			'enabled' => 'false'
		),

		'shareUrl' => '',
		'shareTitle' => '',
		'shareImage' => '',

		'layout' => 1,
		'icons' => 'FontAwesome',
		'skin' => 'light',
		'useFontAwesome5' => 'true',
		'sideNavigationButtons' => 'true',
		'menuNavigationButtons' => 'false',
		'backgroundColor' => 'rgb(81, 85, 88)',
		'backgroundPattern' => '',
		'backgroundImage' => '',
		'backgroundTransparent' => 'false',

		'menuBackground' => '',
		'menuShadow' => '',
		'menuMargin' => '0',
		'menuPadding' => '0',
		'menuOverBook' => 'false',
		'menuFloating' => 'false',
		'menuTransparent' => 'false',

		'menu2Background' => '',
		'menu2Shadow' => '',
		'menu2Margin' => '0',
		'menu2Padding' => '0',
		'menu2OverBook' => 'true',
		'menu2Floating' => 'false',
		'menu2Transparent' => 'true',

		'skinColor' => '',
		'skinBackground' => '',

		'hideMenu' => 'false',
		'menuAlignHorizontal' => 'center',
		'btnColor' => '',
		'btnColorHover' => '',
		'btnBackground' => 'none',
		'btnRadius' => '0',
		'btnMargin' => '0',
		'btnSize' => '18',
		'btnPaddingV' => '10',
		'btnPaddingH' => '10',
		'btnShadow' => '',
		'btnTextShadow' => '',
		'btnBorder' => '',
		'sideBtnColor' => '#fff',
		'sideBtnColorHover' => '#fff',
		'sideBtnBackground' => 'rgba(0,0,0,.3)',
		'sideBtnBackgroundHover' => '',
		'sideBtnRadius' => '0',
		'sideBtnMargin' => '0',
		'sideBtnSize' => '25',
		'sideBtnPaddingV' => '10',
		'sideBtnPaddingH' => '10',
		'sideBtnShadow' => '',
		'sideBtnTextShadow' => '',
		'sideBtnBorder' => '',
		'closeBtnColorHover' => '#FFF',
		'closeBtnBackground' => 'rgba(0,0,0,.4)',
		'closeBtnRadius' => '0',
		'closeBtnMargin' => '0',
		'closeBtnSize' => '20',
		'closeBtnPadding' => '5',
		'closeBtnTextShadow' => '',
		'closeBtnBorder' => '',
		'floatingBtnColor' => '',
		'floatingBtnColorHover' => '',
		'floatingBtnBackground' => '',
		'floatingBtnBackgroundHover' => '',
		'floatingBtnRadius' => '',
		'floatingBtnMargin' => '',
		'floatingBtnSize' => '',
		'floatingBtnPadding' => '',
		'floatingBtnShadow' => '',
		'floatingBtnTextShadow' => '',
		'floatingBtnBorder' => '',
		'currentPageMarginV' => '5',
		'currentPageMarginH' => '5',
		'arrowsAlwaysEnabledForNavigation' => 'true',
		'arrowsDisabledNotFullscreen' => 'true',
		'touchSwipeEnabled' => 'true',
		'rightClickEnabled' => 'true',
		'linkColor' => 'rgba(0, 0, 0, 0)',
		'linkColorHover' => 'rgba(255, 255, 0, 1)',
		'linkOpacity' => '0.4',
		'linkTarget' => '_blank',
		'pdfAutoLinks' => 'false',
		'disableRange' => 'false',

		'strings' => array(
			'print' => __('Print', 'real3d-flipbook'),
			'printLeftPage' => __('Print left page', 'real3d-flipbook'),
			'printRightPage' => __('Print right page', 'real3d-flipbook'),
			'printCurrentPage' => __('Print current page', 'real3d-flipbook'),
			'printAllPages' => __('Print all pages', 'real3d-flipbook'),
			'download' => __('Download', 'real3d-flipbook'),
			'downloadLeftPage' => __('Download left page', 'real3d-flipbook'),
			'downloadRightPage' => __('Download right page', 'real3d-flipbook'),
			'downloadCurrentPage' => __('Download current page', 'real3d-flipbook'),
			'downloadAllPages' => __('Download all pages', 'real3d-flipbook'),
			'bookmarks' => __('Bookmarks', 'real3d-flipbook'),
			'bookmarkLeftPage' => __('Bookmark left page', 'real3d-flipbook'),
			'bookmarkRightPage' => __('Bookmark right page', 'real3d-flipbook'),
			'bookmarkCurrentPage' => __('Bookmark current page', 'real3d-flipbook'),
			'search' => __('Search', 'real3d-flipbook'),
			'findInDocument' => __('Find in document', 'real3d-flipbook'),
			'pagesFoundContaining' => __('pages found containing', 'real3d-flipbook'),
			'noMatches' => __('No matches', 'real3d-flipbook'),
			'matchesFound' => __('matches found', 'real3d-flipbook'),
			'page' => __('Page', 'real3d-flipbook'),
			'matches' => __('matches', 'real3d-flipbook'),
			'thumbnails' => __('Thumbnails', 'real3d-flipbook'),
			'tableOfContent' => __('Table of Contents', 'real3d-flipbook'),
			'share' => __('Share', 'real3d-flipbook'),
			'pressEscToClose' => __('Press ESC to close', 'real3d-flipbook'),
			'password' => __('Password', 'real3d-flipbook'),
			'addNote' => __('Add note', 'real3d-flipbook'),
			'typeInYourNote' => __('Type in your note...', 'real3d-flipbook'),
		),

		'access' => 'free', //free, woo_subscription, ...
		'backgroundMusic' => '',
		'cornerCurl' => 'false',
		'pdfTools' => array(
			'pageHeight' => 1500,
			'thumbHeight' => 200,
			'quality' => 0.8,
			'textLayer' => 'true',
			'autoConvert' => 'true'
		),
		'slug' => '',
		'convertPDFLinks' => 'true',
		'convertPDFLinksWithClass' => '',
		'convertPDFLinksWithoutClass' => '',
		'overridePDFEmbedder' => 'true',
		'overrideDflip' => 'true',
		'overrideWonderPDFEmbed' => 'true',
		'override3DFlipBook' => 'true',
		'overridePDFjsViewer' => 'true',
		'resumeReading' => 'false',
		'previewPages' => '',
		'previewMode' => '',
	);
}

Real3DFlipbook::get_instance();
