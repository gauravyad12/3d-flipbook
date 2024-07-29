<?php

class R3D_Post_Type
{

	public static $instance;

	public $main;

	public function __construct()
	{

		$this->main = Real3DFlipbook::get_instance();

		$labels = array(
			'name'               => __('Real3D Flipbook', 'real3d-flipbook'),
			'singular_name'      => __('Real3D Flipbook', 'real3d-flipbook'),
			'menu_name'          => __('Real3D Flipbook', 'real3d-flipbook'),
			'name_admin_bar'     => __('Real3D Flipbook', 'real3d-flipbook'),
			'add_new'            => __('Add New', 'real3d-flipbook'),
			'add_new_item'       => __('Add New Flipbook', 'real3d-flipbook'),
			'new_item'           => __('New Book', 'real3d-flipbook'),
			'edit_item'          => __('Edit Book', 'real3d-flipbook'),
			'view_item'          => __('View Book', 'real3d-flipbook'),
			'all_items'          => __('Flipbooks', 'real3d-flipbook'),
			'search_items'       => __('Search', 'real3d-flipbook'),
			'parent_item_colon'  => __('Parent Book:', 'real3d-flipbook'),
			'not_found'          => __('Flipbook Not found.', 'real3d-flipbook'),
			'not_found_in_trash' => __('Flipbook Not found in Trash.', 'real3d-flipbook')
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __('Description.', 'real3d-flipbook'),
			'public'             => true,  //this removes the permalink option
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 81,
			'menu_icon'          => 'dashicons-book',
			'supports'           => array('title', 'thumbnail', 'slug', 'author'),
			'exclude_from_search' => true
		);

		$real3dflipbook_global = $this->main->getFlipbookGlobal();

		$rewriteSlug = 'flipbook';

		if (!empty($real3dflipbook_global["slug"])) {
			$rewriteSlug = $real3dflipbook_global["slug"];
		}

		$args['rewrite'] = array(
			'slug' => $rewriteSlug,
			'with_front' => false
		);

		register_post_type('r3d', $args);

		if(get_option('r3d_flush_rewrite_rules')){
			flush_rewrite_rules();
			update_option('r3d_flush_rewrite_rules', false);
		}


		$categories_labels = array(
			'name' => __('Flipbook Categories', 'taxonomy general name'),
			'singular_name' => __('Flipbook Category', 'taxonomy singular name'),
			'search_items' =>  __('Search Categories', 'real3d-flipbook'),
			'all_items' => __('All Categories', 'real3d-flipbook'),
			'edit_item' => __('Edit Categories', 'real3d-flipbook'),
			'update_item' => __('Update Category', 'real3d-flipbook'),
			'add_new_item' => __('Add New Category', 'real3d-flipbook'),
			'new_item_name' => __('New Category', 'real3d-flipbook'),
			'menu_name' => __('Categories', 'real3d-flipbook')
		);

		register_taxonomy('r3d_category', 'r3d', array(
			'labels'             => $categories_labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'rewrite'           => array('slug' => 'r3d_category'),
		));



		$author_labels = array(
			'name' => __('Flipbook Authors', 'taxonomy general name'),
			'singular_name' => __('Flipbook Author', 'taxonomy singular name'),
			'search_items' =>  __('Search Authors', 'real3d-flipbook'),
			'all_items' => __('All Authors', 'real3d-flipbook'),
			'edit_item' => __('Edit Author', 'real3d-flipbook'),
			'update_item' => __('Update Author', 'real3d-flipbook'),
			'add_new_item' => __('Add New Author', 'real3d-flipbook'),
			'new_item_name' => __('New Author', 'real3d-flipbook'),
			'menu_name' => __('Authors', 'real3d-flipbook')
		);

		register_taxonomy('r3d_author', 'r3d', array(
			'labels'             => $author_labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'rewrite'           => array('slug' => 'r3d_author'),
			'parent_item'                => null,
			'parent_item_colon'          => null
		));


		if (is_admin()) {
			$this->init_admin();
		}
	}

	public function init_admin()
	{

		// Remove quick editing from the r3d post type row actions.
		// add_filter( 'post_row_actions', array( $this, 'custom_actions' ), 10, 1 );

		add_filter('manage_r3d_posts_columns', array($this, 'r3d_columns'));
		add_action('manage_r3d_posts_custom_column', array($this, 'r3d_columns_content'), 10, 2);

		add_filter('manage_edit-r3d_category_columns', array($this, 'r3d_cat_columns'));
		add_filter('manage_r3d_category_custom_column', array($this, 'r3d_cat_columns_content'), 10, 3);

		add_filter('post_row_actions', array($this, 'duplicate_post_link'), 10, 2);

		add_action('admin_action_r3d_duplicate_post', array($this, 'duplicate_post'));


		add_action('before_delete_post', array($this, 'deleted_post'));

		if (!get_option('r3d_posts_generated')) {

			$real3dflipbooks_ids = get_option('real3dflipbooks_ids');
			if (is_array($real3dflipbooks_ids)) {
				foreach ($real3dflipbooks_ids as $real3dflipbooks_id) {
					$book = get_option('real3dflipbook_' . $real3dflipbooks_id);
					if ($book && (!isset($book['post_id']) || !get_post_status($book['post_id']))) {
						$args = [
							'post_title'    => $book["name"],
							'post_type'     => 'r3d',
							'post_status'   => 'publish',
							'meta_input'    => [
								'flipbook_id' => $real3dflipbooks_id,
							],
						];

						if (isset($book['date'])) {
							$args['post_date'] = $book['date'];
						}

						$postId = wp_insert_post($args);

						$book["post_id"] = $postId;
						update_option('real3dflipbook_' . $real3dflipbooks_id, $book);
					}
				}
			}
		}

		update_option('r3d_posts_generated', true);
	}

	public function deleted_post($post_id)
	{
		$post = get_post($post_id);
		$current_id = get_post_meta($post_id, 'flipbook_id', true);

		if ($current_id) {

			$real3dflipbooks_ids = get_option('real3dflipbooks_ids');
			delete_option('real3dflipbook_' . (string)$current_id);
			$real3dflipbooks_ids = array_diff($real3dflipbooks_ids, array($current_id));
			update_option('real3dflipbooks_ids', $real3dflipbooks_ids);
		}
	}

	public function custom_actions($actions)
	{
		
		if (isset(get_current_screen()->post_type) && 'r3d' == get_current_screen()->post_type) {
			unset($actions['inline hide-if-no-js']);

			$actions['duplicate'] = '<a href="">Duplicate</a>';
		}
		
		return $actions;
	}

	public function duplicate_post()
	{
		global $wpdb;
		if (!(isset($_GET['post']) || isset($_POST['post'])  || (isset($_REQUEST['action']) && 'r3d_duplicate_post_as_draft' == $_REQUEST['action']))) {
			wp_die('No post to duplicate has been supplied!');
		}

		if (!isset($_GET['duplicate_nonce']) || !wp_verify_nonce($_GET['duplicate_nonce'], basename(__FILE__)))
			return;

		$post_id = (isset($_GET['post']) ? absint($_GET['post']) : absint($_POST['post']));
		
		$post = get_post($post_id);

		$current_user = wp_get_current_user();
		$new_post_author = $current_user->ID;

		if (isset($post) && $post != null) {

			$current_id = get_post_meta($post_id, 'flipbook_id', true);

			$current = get_option('real3dflipbook_' . $current_id);

			$real3dflipbooks_ids = get_option('real3dflipbooks_ids');
			if (!empty($real3dflipbooks_ids)) {
				$real3dflipbooks_ids = array_map('intval', $real3dflipbooks_ids);
				$new_id = max($real3dflipbooks_ids) + 1;
			}

			$new = $current;
			$new["id"] = $new_id;
			$new["name"] = $current["name"] . " (copy)";
			$new["date"] = current_time('mysql');

			array_push($real3dflipbooks_ids, $new_id);
			update_option('real3dflipbooks_ids', $real3dflipbooks_ids);

			$args = array(
				'post_title' => $post->post_title . ' (copy)',
				'post_type' => 'r3d',

				// 'post_content'=>'demo text',
				'post_status'   => 'publish',
				'meta_input' => array(
					'flipbook_id' => $new_id
				)
			);

			$new_post_id = wp_insert_post($args);

			//save post id to book
			$new["post_id"] = $new_post_id;
			update_option('real3dflipbook_' . (string)$new_id, $new);

			$taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
			foreach ($taxonomies as $taxonomy) {
				$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
				wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
			}

			
			wp_redirect(admin_url('edit.php?post_type=r3d'));
			exit;
		} else {
			wp_die('Post creation failed, could not find original post: ' . $post_id);
		}
	}

	public function duplicate_post_link($actions, $post)
	{
		
		if (current_user_can('edit_posts') && isset(get_current_screen()->post_type) && 'r3d' == get_current_screen()->post_type) {
			$actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=r3d_duplicate_post&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce') . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
		}
		
		return $actions;
	}



	public function r3d_columns()
	{

		$columns = array(
			'cb'        => '<input type="checkbox" />',
			'cover' => __('Cover', 'r3dfb'),
			'title'     => __('Title', 'r3dfb'),
			'shortcode' => __('Shortcode', 'r3dfb'),
			// 'permalink' => __( 'Permalink', 'r3dfb' ),
			'date'      => __('Date', 'r3dfb'),
			'author'      => __('Author', 'r3dfb')
		);

		return $columns;
	}

	public function r3d_cat_columns($defaults)
	{
		$defaults['shortcode'] = 'Shortcode';
		// $defaults['cover'] = 'Cover';

		return $defaults;
	}

	public function r3d_columns_content($column_name, $post_id)
	{

		$post_id = absint($post_id);

		$id = get_post_meta($post_id, 'flipbook_id', true);

		switch ($column_name) {
			case 'shortcode':
				echo '<code>[real3dflipbook id="' . esc_attr($id) . '"]</code>  <div id="' . esc_attr($id) . '" class="button-secondary copy-shortcode">Copy</div>';
				break;

				// case 'permalink':
				//   echo '<a href="'.esc_url(get_permalink($post_id)).'">'.esc_url(get_permalink($post_id)).'</a>';
				//   break;

			case 'cover':
				$book = get_option('real3dflipbook_' . $id);
				$thumb = 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=';
				if (isset($book['lightboxThumbnailUrl']))
					$thumb = $book['lightboxThumbnailUrl'];
				echo '<div class="thumb" style=";background-image:url(' . esc_url($thumb) . ');"><a href="#" class="edit" name="' . esc_attr($id) . '"></a></div>';
				break;
		}
	}

	public function r3d_cat_columns_content($c, $column_name, $term_id = "")
	{

		return '<code>[real3dflipbook category="' . get_term($term_id, 'r3d_category')->slug . '"]</code>   <div id="' . get_term($term_id, 'r3d_category')->slug . '" class="button-secondary copy-shortcode">Copy</div>';
	}

	public static function get_instance()
	{

		if (!isset(self::$instance) && !(self::$instance instanceof R3D_Post_Type)) {
			self::$instance = new R3D_Post_Type();
		}

		return self::$instance;
	}
}

// Load the post-type class.
$r3d_post_type = R3D_Post_Type::get_instance();
