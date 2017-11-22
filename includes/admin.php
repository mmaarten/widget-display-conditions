<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

/**
 * Widget Form
 */
function wdc_widget_form( $widget, $return, $instance )
{
	$locations = isset( $instance['wdc_conditions'] ) ? (array) $instance['wdc_conditions'] : array();

	?>

		<input type="hidden" class="wdc-conditions" name="<?php echo $widget->get_field_name( 'wdc_conditions' ); ?>" value="<?php echo esc_attr( json_encode( $locations ) ); ?>">

		<p>
			<button type="button" class="button wdc-settings-button"><?php _e( 'Display conditions', 'wdc' ); ?></button>
		</p>

	<?php
}

add_action( 'in_widget_form', 'wdc_widget_form', 99, 3 );

/**
 * Widget Update
 *
 * Called when the widget form has been submitted.
 */
function wdc_widget_update( $instance, $new_instance, $old_instance, $widget )
{
	if ( ! empty( $new_instance['wdc_conditions'] ) ) 
	{
		$instance['wdc_conditions'] = json_decode( $new_instance['wdc_conditions'] );
	}

	else
	{
		$instance['wdc_conditions'] = array();
	}

	return $instance;
}

add_action( 'widget_update_callback', 'wdc_widget_update', 10, 4 );

/**
 * Enqueue Scripts
 */
function wdc_enqueue_scripts( $hook )
{
	// Checks if widgets screen
	if ( $hook != 'widgets.php' ) 
	{
		return;
	}

	// Vendor
	wp_enqueue_script( 'wp-util' );
	wp_enqueue_script( 'featherlight', plugins_url( 'vendor/featherlight/featherlight.js', WDC_FILE ), array( 'jquery' ), '1.7.9', true );
	wp_enqueue_script( 'serialize-object', plugins_url( 'vendor/serialize-object/serialize-object.js', WDC_FILE ), array( 'jquery' ), false, true );

	// Ours assets
	wp_enqueue_style( 'widget-display-conditions', plugins_url( 'css/main.css', WDC_FILE ) );
	wp_enqueue_script( 'widget-display-conditions', plugins_url( 'js/main.js', WDC_FILE ), array( 'jquery' ), false, true );
}

add_action( 'admin_enqueue_scripts', 'wdc_enqueue_scripts' );

function wdc_condition_group_field( $group_id, $conditions = array() )
{
	?>

	<div class="condition-group" data-id="<?php echo esc_attr( $group_id ); ?>">

		<h4><?php _e( 'or', 'wdc' ); ?></h4>

		<table class="conditions">
			<?php foreach ( $conditions as $condition ) : ?>
			<?php wdc_condition_field( $condition->id, $group_id ); ?>
			<?php endforeach; ?>
		</table>

	</div>

	<?php
}

function wdc_condition_field( $condition_id, $group_id )
{
	?>

	<tr class="condition" data-id="<?php echo esc_attr( $condition_id ); ?>">
		<td class="param">
			<select name="conditions[<?php echo esc_attr( $group_id ); ?>][<?php echo esc_attr( $condition_id ); ?>][param]">
				<optgroup label="<?php esc_attr_e( 'Post', 'wdc' ); ?>">
					<option value="post"><?php esc_html_e( 'Post', 'wdc' ); ?></option>
					<option value="post_type"><?php esc_html_e( 'Post type', 'wdc' ); ?></option>
					<option value="post_term"><?php esc_html_e( 'Post term', 'wdc' ); ?></option>
					<option value="post_archive"><?php esc_html_e( 'Post archive', 'wdc' ); ?></option>
					<option value="post_template"><?php esc_html_e( 'Post template', 'wdc' ); ?></option>
				</optgroup>
				<optgroup label="<?php esc_attr_e( 'Page', 'wdc' ); ?>">
					<option value="page"><?php esc_html_e( 'Page', 'wdc' ); ?></option>
					<option value="page_type"><?php esc_html_e( 'Page type', 'wdc' ); ?></option>
					<option value="page_parent"><?php esc_html_e( 'Page parent', 'wdc' ); ?></option>
					<option value="page_template"><?php esc_html_e( 'Page template', 'wdc' ); ?></option>
				</optgroup>
			</select>
		</td>
		<td class="operator">
			<select name="conditions[<?php echo esc_attr( $group_id ); ?>][<?php echo esc_attr( $condition_id ); ?>][operator]">
				<option value="=="><?php _e( 'is equal to', 'wdc' ); ?></option>
				<option value="!="><?php _e( 'is not equal to', 'wdc' ); ?></option>
			</select>
		</td>
		<td class="value">
			<select name="conditions[<?php echo esc_attr( $group_id ); ?>][<?php echo esc_attr( $condition_id ); ?>][value]"></select>
		</td>
		<td class="and">
			<button class="button"><?php _e( 'And', 'wdc' ); ?></button>
		</td>
		<td class="remove">
			<button class="button"><?php _e( 'Remove', 'wdc' ); ?></button>
		</td>
	</tr>

	<?php
}

/**
 * Print Scripts
 */
function wdc_print_scripts()
{
	// Checks if widgets screen

	$screen = get_current_screen();
	
	if ( $screen->id != 'widgets' ) 
	{
		return;
	}

	?>

	<script id="tmpl-wdc-settings" type="text/html">
		
		<div class="wdc-settings">

			<h1><?php _e( 'Display Conditions', 'wdc' ); ?></h1>

			<div class="notice notice-info wdc-hide-if-conditions">
				<p><strong><?php _e( 'No conditions set.', 'wdc' ); ?></strong> <?php _e( 'Widget will be displayed on any page.' ); ?></p>
			</div>

			<div class="notice notice-info wdc-show-if-conditions">
				<p><strong><?php _e( 'Widget will be displayed when following conditions are met.', 'wdc' ); ?></strong></p>
			</div>

			<form method="post">
				
				<div class="condition-groups"></div>

				<p>
					<button type="button" class="button add-condition-group"><?php _e( 'Add condition group', 'wdc' ); ?></button>
				</p>

				<p class="wdc-submit">
					<button type="submit" class="button button-primary"><?php _e( 'Update', 'wdc' ); ?></button>
				</p>

			</form>

		</div><!-- .wdc-widget-conditions -->

	</script>

	<script id="tmpl-wdc-condition-group" type="text/html">

		<?php wdc_condition_group_field( '{{data.id}}' ); ?>
		
	</script>

	<script id="tmpl-wdc-condition" type="text/html">
		
		<?php wdc_condition_field( '{{ data.id }}', '{{ data.group }}' ); ?>

	</script>

	<?php
}

add_action( 'admin_print_scripts', 'wdc_print_scripts' );

/**
 * Get Rule Parameter Values
 */
function wdc_get_condition_param_values()
{
	if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) 
	{
		return;
	}

	$param = isset( $_POST['param'] ) ? $_POST['param'] : '';

	$items = array();

	switch ( $param ) 
	{
		case 'post_type':
			
			$post_types = get_post_types( array( 'public' => true ), 'objects' );

			foreach ( $post_types as $post_type ) 
			{
				$items[] = array
				(
					'id'   => $post_type->name,
					'text' => $post_type->labels->singular_name
				);
			}

			break;

		case 'post':
			
			$post_types = get_post_types( array( 'public' => true ), 'objects' );

			$exclude = array( 'page', 'attachment' );

			foreach ( $post_types as $post_type ) 
			{
				if ( in_array( $post_type->name, $exclude ) ) 
				{
					continue;
				}

				$posts = get_posts( array
				(
					'post_type'      => $post_type->name,
					'posts_per_page' => WDC_MAX_NUMBERPOSTS
				));

				if ( ! count( $posts ) ) 
				{
					continue;
				}

				$items[ $post_type->name ] = array
				(
					'text'     => $post_type->labels->singular_name,
					'children' => array()
				);

				foreach ( $posts as $post ) 
				{
					$items[ $post_type->name ]['children'][] = array
					(
						'id'   => $post->ID,
						'text' => $post->post_title
					);
				}
			}

			break;

		case 'post_template' :
		case 'page_template' :

			$items[] = array
			(
				'id'   => 'default',
				'text' => __( 'default', 'wdc' )
			);

			$templates = wp_get_theme()->get_page_templates();

			foreach ( $templates as $template_file => $template_name ) 
			{
				$items[] = array
				(
					'id'   => $template_file,
					'text' => $template_name
				);
			}

			break;

		case 'post_term':
			
			$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );

			foreach ( $taxonomies as $taxonomy ) 
			{
				$terms = get_terms( array
				(
					'taxonomy' => $taxonomy->name
				));

				if ( ! count( $terms ) ) 
				{
					continue;
				}

				$items[ $taxonomy->name ] = array
				(
					'text'     => $taxonomy->labels->singular_name,
					'children' => array()
				);

				foreach ( $terms as $term ) 
				{
					$items[ $taxonomy->name ]['children'][] = array
					(
						'id'   => $term->term_id,
						'text' => $term->name
					);
				}
			}

			break;

		case 'page':
		case 'page_parent':
			
			$posts = get_pages( array
			(
				'post_type'      => 'page',
				'hierarchical'   => true,
				'posts_per_page' => WDC_MAX_NUMBERPOSTS
			));
			
			foreach ( $posts as $post )
			{
				$ancestors = get_ancestors( $post->ID, 'page' );

				$items[] = array
				(
					'id'   => $post->ID,
					'text' => sprintf( '%s %s', str_repeat( '–', count( $ancestors ) ), $post->post_title )
				);
			}

			break;

		case 'page_type':
			
			$items[] = array
			(
				'id'   => 'front_page',
				'text' => __( 'Front Page', 'wdc' )
			);

			$items[] = array
			(
				'id'   => 'posts_page',
				'text' => __( 'Posts Page', 'wdc' )
			);

			$items[] = array
			(
				'id'   => 'top_level',
				'text' => __( 'Top Level Page (no parent)', 'wdc' )
			);

			$items[] = array
			(
				'id'   => 'parent',
				'text' => __( 'Parent Page (has children)', 'wdc' )
			);

			$items[] = array
			(
				'id'   => 'child',
				'text' => __( 'Child Page (has parent)', 'wdc' )
			);

			$items[] = array
			(
				'id'   => 'search_page',
				'text' => __( 'Search Page', 'wdc' )
			);

			$items[] = array
			(
				'id'   => '404_page',
				'text' => __( '404 Page (Not Found)', 'wdc' )
			);

			$items[] = array
			(
				'id'   => 'date_page',
				'text' => __( 'Date Page', 'wdc' )
			);

			$items[] = array
			(
				'id'   => 'author_page',
				'text' => __( 'Author Page', 'wdc' )
			);

			break;

		case 'post_archive':
			
			$items[] = array
			(
				'id'   => '',
				'text' => __( 'All', 'wdc' )
			);

			$post_types = get_post_types( array( 'public' => true ), 'objects' );

			foreach ( $post_types as $post_type )
			{
				if ( ! $post_type->has_archive ) 
				{
					continue;
				}

				$items[] = array
				(
					'id'   => $post_type->name,
					'text' => $post_type->labels->singular_name
				);
			}

			break;
	}

	$items = apply_filters( 'wdc_condition_param_values', $items, $param );

	wp_send_json( $items );
}

add_action( 'wp_ajax_wdc_get_condition_param_values', 'wdc_get_condition_param_values' );


