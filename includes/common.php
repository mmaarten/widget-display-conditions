<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exits when accessed directly

function wdc()
{
	static $instance = null;

	if ( ! $instance ) 
	{
		$instance = new WDC();
	}

	return $instance;
}

/**
 * Get Widget Instance
 *
 * Returns an array containing widget settings.
 *
 * @param string $widget_id The widget id.
 * @return array Widget settings.
 */
function wdc_get_widget_instance( $widget_id )
{
	if ( ! preg_match( '/(.*?)-(\d+)$/', $widget_id, $matches ) ) 
	{
		return null;
	}

	list( , $id_base, $num ) = $matches;

	$instances = get_option( "widget_$id_base" );

	if ( ! is_array( $instances ) || ! isset( $instances[ $num ] ) ) 
	{
		return null;
	}

	return (array) $instances[ $num ];
}

function wdc_get_widget_conditions( $widget_id )
{
	$instance = wdc_get_widget_instance( $widget_id );

	if ( ! $instance ) 
	{
		return null;
	}

	if ( isset( $instance['wdc_conditions'] ) ) 
	{
		return (array) $instance['wdc_conditions'];
	}

	return array();
}

function wdc_post_choices( $args = null )
{
	$defaults = array
	(
		'post_type'   => 'post',
		'group'       => false,
		'post_status' => 'any'
	);

	$args = wp_parse_args( $args, $defaults );

	$choices = array();

	$post_types = (array) $args['post_type'];

	foreach ( $post_types as $post_type ) 
	{
		$post_type = get_post_type_object( $post_type );

		if ( ! $post_type ) 
		{
			continue;
		}

		$is_post_type_hierarchical = is_post_type_hierarchical( $post_type->name );

		if ( $is_post_type_hierarchical ) 
		{
			$posts = get_pages( array
			(
				'post_type'    => $post_type->name,
				'hierarchical' => true,
				//'post_status'  => $args['post_status'], Does not work.
				'numberposts'  => WDC_MAX_NUMBERPOSTS
			));
		}

		else
		{
			$posts = get_posts( array
			(
				'post_type'   => $post_type->name,
				'orderby'     => 'post_title',
				'order'       => 'ASC',
				'post_status' => $args['post_status'],
				'numberposts' => WDC_MAX_NUMBERPOSTS
			));
		}

		if ( ! count( $posts ) ) 
		{
			continue;
		}

		if ( $args['group'] ) 
		{
			// Creates `<optgroup>`.

			$choices[ $post_type->name ] = array
			(
				'text'     => $post_type->labels->singular_name,
				'children' => array()
			);

			$parent = &$choices[ $post_type->name ]['children'];
		}

		else
		{
			$parent = &$choices;
		}

		// Creates `<option>` elements.

		foreach ( $posts as $post ) 
		{
			$prefix = '';

			// Shows hierarchy

			if ( $is_post_type_hierarchical ) 
			{
				$ancestors = get_ancestors( $post->ID, $post->post_type, 'post_type' );

				$prefix = str_repeat( '–', count( $ancestors ) ) . ' ';
			}

			//

			$parent[] = array
			(
				'id'   => $post->ID,
				'text' => $prefix . $post->post_title
			);
		}
	}

	return $choices;
}

function wdc_term_choices( $args )
{
	$defaults = array
	(
		'taxonomy' => '',
		'group'    => false
	);

	$args = wp_parse_args( $args, $defaults );

	$choices = array();

	if ( $args['taxonomy'] ) 
	{
		$taxonomies = (array) $args['taxonomy'];
	}

	else
	{
		$taxonomies = get_taxonomies( array( 'public' => true ), 'names' );
	}

	foreach ( $taxonomies as $taxonomy_name ) 
	{
		$taxonomy = get_taxonomy( $taxonomy_name );

		if ( ! $taxonomy ) 
		{
			continue;
		}

		$is_taxonomy_hierarchical = is_taxonomy_hierarchical( $taxonomy->name );

		if ( $is_taxonomy_hierarchical ) 
		{
			$terms = get_categories( array
			(
				'taxonomy' => $taxonomy->name
			));
		}

		else
		{
			$terms = get_terms( array
			(
				'taxonomy' => $taxonomy->name,
				'orderby'  => 'name',
				'order'    => 'ASC'
			));
		}

		// Checks if posts.

		if ( ! count( $terms ) ) 
		{
			continue;
		}

		if ( $args['group'] ) 
		{
			// Creates `<optgroup>`.

			$choices[ $taxonomy->name ] = array
			(
				'text'     => $taxonomy->labels->singular_name,
				'children' => array()
			);

			$parent = &$choices[ $taxonomy->name ]['children'];
		}

		else
		{
			$parent = &$choices;
		}

		// Creates `<option>` elements.

		foreach ( $terms as $term ) 
		{
			$prefix = '';

			// Shows hierarchy

			if ( $is_taxonomy_hierarchical ) 
			{
				$ancestors = get_ancestors( $term->term_id, $term->taxonomy, 'taxonomy' );

				$prefix = str_repeat( '–', count( $ancestors ) ) . ' ';
			}

			//

			$parent[] = array
			(
				'id'   => $term->term_id,
				'text' => $prefix. $term->name
			);
		}
	}

	return $choices;
}

function wdc_user_choices( $args = null )
{
	$defaults = array
	(
		'role' => '',
		'who'  => ''
	);

	$args = wp_parse_args( $args, $defaults );

	$choices = array();

	$users = get_users( array
	(
		'role'    => $args['role'],
		'who'     => $args['who'],
		'orderby' => 'display_name',
		'order'   => 'ASC'
	));

	foreach ( $users as $user ) 
	{
		$choices[] = array
		(
			'id'   => $user->ID,
			'text' => $user->display_name
		);
	}

	return $choices;
}

function wdc_post_type_choices( $args = null )
{
	$defaults = array
	(
		'public'      => true,
		'has_archive' => null
	);

	$args = wp_parse_args( $args, $defaults );

	$choices = array();

	$post_types = get_post_types( array
	(
		'public' => $args['public']
	), 'objects' );

	foreach ( $post_types as $post_type ) 
	{
		if ( ! is_null( $args['has_archive'] ) )
		{
			if ( $args['has_archive'] && ! $post_type->has_archive ) 
			{
				continue;
			}

			elseif ( ! $args['has_archive'] && $post_type->has_archive ) 
			{
				continue;
			}
		}

		$choices[] = array
		(
			'id'   => $post_type->name,
			'text' => $post_type->labels->singular_name
		);
	}

	return $choices;
}

function wdc_page_template_choices()
{
	$choices = array
	(
		array
		(
			'id'   => 'default',
			'text' => __( 'Default', 'wdc' )
		)
	);

	$page_templates = get_page_templates();

	foreach ( $page_templates as $template_name => $template_file ) 
	{
		$choices[] = array
		(
			'id'   => $template_file,
			'text' => $template_name
		);
	}

	return $choices;
}
