<?php
/*
Plugin Name: Food Recipes
Plugin URI: http://blog.omerfarukak.com/yemek-tarifleri
Description: Custom post type for food recipes.
Version: 1.0.1
Author: Ömer Faruk AK
Author URI: http://omerfarukak.com
*/

class AK_Cook_Recipes
{
	public function __construct()
	{
		$this->register_post_type();
		$this->taxonomies();
		$this->metaboxes();
		register_taxonomy_for_object_type('category', 'ak_cook');
	}

	public function register_post_type()
	{
		$args = array(
			'public' => true,
			'label' => 'Cook Recipes',
			'labels' => array('add_new_item' => 'Add New Recipe',
					'name' => 'Cook Recipes',
					'singular_name' => 'Cook Recipe',
					'add_new' => 'Add New Recipe',
					'menu_name' => 'Cook Recipes'
					),
			'rewrite' => array( 'slug' => 'recipes' ),
			'supports' => array('title', 'editor', 'thumbnail', 'comments'),
			'description' => 'You can use this type for cook recipes.',
			'menu_position' => 5,
			'menu_icon' => plugin_dir_path(__FILE__) . '/images/food.png'
		);
		
		register_post_type('ak_cook', $args);
	}

	public function taxonomies()
	{
		$taxonomies = array();
		
		$taxonomies['tur'] = array(
		'hierarchical' => true,
		'query_var' => 'cook_cat',
		'rewrite' => array(
				'slug' => 'cook/cat'
		),
		'labels' => array(
				'name' => 'Cook Categories',
				'singular_name' => 'Cook Category',
				'edit_item' => 'Edit Category',
				'update_item' => 'Update Category',
				'add_new_item' => 'Add Cook Category',
				'new_item_name' => 'Add New Cook Category',
				'all_items' => 'All Categories',
				'search_items' => 'Search in Cooks',
				'populer_items' => 'Popular Cooks',
				'add_or_remove_items' => 'Add or remove'
			)
		);

		$this->register_all_taxonomies($taxonomies);
	}

	public function register_all_taxonomies($taxonomies)
	{
		foreach($taxonomies as $name => $arr ){
			register_taxonomy($name, array('ak_cook'), $arr);
		}
	}

	public function metaboxes()
	{
		add_action('add_meta_boxes', function(){
			//css id, title, cb func, page, priority, cb func args
			add_meta_box('ak_cook_malzemeler', 'Malzemeler', 'yemek_malzemeleri', 'ak_cook');
		});

		function yemek_malzemeleri($post)
		{
			$malzemeler = get_post_meta($post->ID, 'ak_cook_malzemeler', true);
		?>
			<p>
				<label for="ak_cook_malzemeler"> Malzemeler : </label>
				<textarea name="ak_cook_malzemeler" id="ak_cook_malzemeler"  rows="15" cols="100" class="widefat"><?php echo esc_attr($malzemeler); ?></textarea>
			</p>
		<?php
		}

		add_action('save_post', function($id){
			if ( isset($_POST['ak_cook_ingredients']) )
			{
				update_post_meta(
					$id,
					'ak_cook_ingredients',
					strip_tags($_POST['ak_cook_ingredients'])
				);
			}
		});
	}
}

add_action('init', function(){
		new AK_Cook_Recipes();
});

?>
